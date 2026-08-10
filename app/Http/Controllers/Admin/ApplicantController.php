<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Admin\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query dengan Eager Loading
        $query = Application::with(['applicant.user', 'lowongan']);

        // 2. SCOPE DATA: Filter berdasarkan cabang (PENTING)
        // Jika bukan superadmin, hanya tampilkan lamaran untuk lowongan di cabangnya
        if (Auth::user()->role !== 'superadmin') {
            $query->whereHas('lowongan', function($q) {
                $q->where('cabang_id', Auth::user()->cabang_id);
            });
        }

        // 3. Filter Kategori
        if ($request->filled('category')) {
            $query->whereHas('lowongan', function($q) use ($request) {
                $q->where('kategori', $request->category);
            });
        }

        // 4. Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 5. Filter Periode Melamar
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // 6. Hitung Stats (Angka Statistik juga harus terfilter)
        $statsQuery = Application::query();
        if (Auth::user()->role !== 'superadmin') {
            $statsQuery->whereHas('lowongan', function($q) {
                $q->where('cabang_id', Auth::user()->cabang_id);
            });
        }
        
        // Tambahkan filter kategori/tanggal ke stats agar angka sinkron dengan tabel
        if ($request->filled('category')) {
            $statsQuery->whereHas('lowongan', function($q) use ($request) {
                $q->where('kategori', $request->category);
            });
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $statsQuery->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $stats = $statsQuery->select('status', DB::raw('count(*) as total'))
                            ->groupBy('status')
                            ->pluck('total', 'status')
                            ->toArray();

        $applications = $query->latest()->paginate(50)->withQueryString();

        return view('admin.applicants.index', compact('applications', 'stats'));
    }

    public function show(Application $application)
    {
        // KEAMANAN: Cek apakah admin punya hak akses ke lamaran ini
        if (Auth::user()->role !== 'superadmin' && $application->lowongan->cabang_id !== Auth::user()->cabang_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat pelamar ini.');
        }

        $application->load(['applicant.user', 'applicant.profile.familyMembers', 'applicant.profile.workExperiences', 'applicant.profile.formalEducations', 'applicant.profile.informalEducations', 'applicant.profile.jobFieldInterests', 'applicant.documents', 'lowongan']);
        return view('admin.applicants.show', compact('application'));
    }

    public function byLowongan($lowonganId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);

        // KEAMANAN: Cek apakah lowongan ini di cabangnya
        if (Auth::user()->role !== 'superadmin' && $lowongan->cabang_id !== Auth::user()->cabang_id) {
            abort(403, 'Akses ditolak.');
        }

        $applications = Application::with(['applicant.user', 'lowongan'])
            ->where('lowongan_id', $lowonganId)
            ->latest()
            ->get();

        return view('admin.applicants.index', compact('applications', 'lowongan'));
    }

    public function updateStage(Request $request)
    {
        // Validasi input
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'next_status' => 'required',
            'rejection_reason' => 'required_if:next_status,rejected',
            'psikotes_type' => 'required_if:next_status,psikotes',
            'psikotes_date' => 'required_if:next_status,psikotes',
            'psikotes_link' => 'required_if:next_status,psikotes',
            'interview_date' => 'required_if:next_status,interview',
            'interview_type' => 'required_if:next_status,interview',
            'interview_link' => 'required_if:next_status,interview',
            'mcu_date' => 'required_if:next_status,mcu',
            'join_date' => 'required_if:next_status,accepted',
        ], [
            'required_if' => 'Kolom :attribute wajib diisi untuk tahapan ini.',
        ]);

        $application = Application::with(['applicant.user', 'lowongan'])->findOrFail($request->application_id);

        // KEAMANAN: Cegah update status jika bukan di cabangnya
        if (Auth::user()->role !== 'superadmin' && $application->lowongan->cabang_id !== Auth::user()->cabang_id) {
            abort(403, 'Akses ditolak.');
        }
        
        $updateData = [
            'status' => $request->next_status,
            'notes' => ($request->next_status === 'rejected') ? $request->rejection_reason : null
        ];
        $application->update($updateData);

        try {
            $userEmail = $application->applicant->user->email;

            if ($request->next_status === 'psikotes') {
                $data = $request->only(['psikotes_date', 'psikotes_link', 'psikotes_token']);
                if ($request->psikotes_type === 'tes_kepribadian') {
                    Mail::to($userEmail)->send(new \App\Mail\TesKepribadianEmail($application, $data));
                } else {
                    Mail::to($userEmail)->send(new \App\Mail\PsikotesEmail($application, $data));
                }
            } elseif ($request->next_status === 'interview') {
                $data = $request->only(['interview_date', 'interview_link', 'interview_location']);
                if ($request->interview_type === 'offline') {
                    Mail::to($userEmail)->send(new \App\Mail\InterviewOfflineEmail($application, $data));
                } elseif ($request->interview_type === 'lanjutan') {
                    Mail::to($userEmail)->send(new \App\Mail\InterviewLanjutanEmail($application, $data));
                } else {
                    Mail::to($userEmail)->send(new \App\Mail\InterviewEmail($application, $data));
                }
            } elseif ($request->next_status === 'offering') {
                Mail::to($userEmail)->send(new \App\Mail\OfferingEmail($application));
            } elseif ($request->next_status === 'mcu') {
                Mail::to($userEmail)->send(new \App\Mail\MCUEmail($application, $request->only(['mcu_date', 'mcu_location_name', 'mcu_location_address'])));
            } elseif ($request->next_status === 'rejected') {
                Mail::to($userEmail)->send(new \App\Mail\RejectedEmail($application, $request->rejection_reason));
            } elseif ($request->next_status === 'accepted') {
                Mail::to($userEmail)->send(new \App\Mail\AcceptedEmail($application, $request->only(['join_date', 'work_location', 'office_address']), $request->office_type));
            }

        } catch (\Exception $e) {
            logger()->error("Email Error: " . $e->getMessage());
            return redirect()->back()->with('success', 'Status berhasil diperbarui, namun email GAGAL dikirim.');
        }

        return redirect()->back()->with('success', 'Status ' . $application->applicant->user->name . ' berhasil diperbarui!');
    }

    public function downloadPdf(Application $application)
    {
        // KEAMANAN: Cek akses PDF
        if (Auth::user()->role !== 'superadmin' && $application->lowongan->cabang_id !== Auth::user()->cabang_id) {
            abort(403);
        }

        $applicant = $application->applicant()->with(['profile.familyMembers', 'profile.workExperiences', 'profile.formalEducations', 'profile.informalEducations', 'profile.jobFieldInterests', 'documents'])->first();
        if (!$applicant) abort(404);

        $pdf = Pdf::loadView('admin.applicants.pdf', [
            'application' => $application,
            'applicant'   => $applicant,
            'profile'     => $applicant->profile,
            'lowongan'    => $application->job,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('BIODATA_' . strtoupper($applicant->user->name) . '.pdf');
    }

    // FITUR UPDATE MASSAL
    public function bulkUpdate(Request $request) 
    {
        $ids = $request->selected_ids;
        $status = $request->status;

        // KEAMANAN BULK: Hanya update data yang di cabangnya (jika bukan superadmin)
        $query = Application::whereIn('id', $ids);
        if (Auth::user()->role !== 'superadmin') {
            $query->whereHas('lowongan', function($q) {
                $q->where('cabang_id', Auth::user()->cabang_id);
            });
        }

        $updatedCount = $query->update(['status' => $status]);
        return back()->with('success', $updatedCount . ' pelamar berhasil diperbarui.');
    }

    // Tambahkan di ApplicantController.php
    public function bulkPrepare(Request $request)
    {
        $ids = $request->selected_ids;
        $status = $request->status;

        // Jika pilih psikotes, jalankan fungsi yang sebelumnya
        if ($status === 'psikotes') {
            $applications = Application::with(['applicant.user', 'lowongan'])->whereIn('id', $ids)->get();
            return view('admin.applicants.bulk_psikotes', compact('applications', 'status'));
        }

        // JIKA INTERVIEW
        if ($status === 'interview') {
            $applications = Application::with(['applicant.user', 'lowongan'])->whereIn('id', $ids)->get();
            return view('admin.applicants.bulk_interview', compact('applications', 'status'));
        }

        // Untuk status lain (Rejected/Administration) bisa langsung update
        return $this->bulkUpdate($request);
    }

    public function bulkProcess(Request $request)
    {
        $data = $request->input('applicants'); // Ambil array dari form

        foreach ($data as $id => $details) {
            $application = Application::findOrFail($id);
            
            // Update Database
            $application->update([
                'status' => 'psikotes',
            ]);

            // Kirim Email
            try {
                $emailData = [
                    'psikotes_date' => $details['date'],
                    'psikotes_link' => $details['link'],
                    'psikotes_token' => $details['token'],
                ];
                
                Mail::to($application->applicant->user->email)
                    ->send(new \App\Mail\PsikotesEmail($application, $emailData));
                    
            } catch (\Exception $e) {
                logger()->error("Gagal kirim email ke ID $id: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.applicants')->with('success', count($data) . ' undangan psikotes berhasil dikirim.');
    }

    public function bulkProcessInterview(Request $request)
    {
        $data = $request->input('applicants');

        foreach ($data as $id => $details) {
            $application = Application::findOrFail($id);
            
            $application->update(['status' => 'interview']);

            try {
                $emailData = [
                    'interview_date' => $details['date'],
                    'interview_type' => $details['type'],
                    'interview_link' => $details['link_atau_lokasi'], // Bisa berisi Link atau Alamat
                    'interview_location' => $details['link_atau_lokasi'], 
                ];
                
                // Pilih Mailable berdasarkan tipe
                if ($details['type'] === 'offline') {
                    Mail::to($application->applicant->user->email)
                        ->send(new \App\Mail\InterviewOfflineEmail($application, $emailData));
                } else {
                    Mail::to($application->applicant->user->email)
                        ->send(new \App\Mail\InterviewEmail($application, $emailData));
                }
                    
            } catch (\Exception $e) {
                logger()->error("Gagal kirim email interview ke ID $id: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.applicants')->with('success', count($data) . ' undangan interview berhasil dikirim.');
    }

    // lihat biodata walau belum apply
    public function downloadProfilePdf($applicantId)
    {
        // Cari applicant berdasarkan ID, muat user dan profilenya
        $applicant = \App\Models\Applicant::with(['user', 'profile.familyMembers', 'profile.workExperiences', 'profile.formalEducations', 'profile.informalEducations', 'profile.jobFieldInterests', 'documents'])->findOrFail($applicantId);
        
        // Gunakan view PDF yang sudah Anda punya
        $pdf = Pdf::loadView('admin.applicants.pdf', [
            'applicant'   => $applicant,
            'profile'     => $applicant->profile,
            'user'        => $applicant->user,
            'application' => null, // Set null karena dia belum melamar ke lowongan manapun
            'lowongan'    => null  // Set null juga
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('BIODATA_' . strtoupper($applicant->user->name) . '.pdf');
    }
}