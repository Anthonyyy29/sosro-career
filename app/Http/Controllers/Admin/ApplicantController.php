<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Admin\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['applicant.user', 'lowongan']);

        // Filter Kategori
        if ($request->filled('category')) {
            $query->whereHas('lowongan', function($q) use ($request) {
                $q->where('kategori', $request->category);
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Periode Melamar
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Update stats agar angkanya juga ikut terfilter periode (Optional, agar angka sinkron)
        $statsQuery = Application::query();
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
        $application->load(['applicant.user', 'applicant.profile', 'lowongan']);

        return view('admin.applicants.show', compact('application'));
    }

    public function downloadPdf(Application $application)
    {
        // Ambil data pelamar + profile
        $applicant = $application->applicant()->with('profile')->first();

        if (!$applicant) {
            abort(404, 'Applicant tidak ditemukan');
        }

        $profile = $applicant->profile;

        // Generate PDF
        $pdf = Pdf::loadView('admin.applicants.pdf', [
            'application' => $application,
            'applicant'   => $applicant,
            'profile'     => $profile,
            'lowongan'    => $application->job,
        ])->setPaper('a4', 'portrait');

        // Nama file sesuai pelamar
        $filename = 'BIODATA_' . strtoupper($applicant->user->name) . '.pdf';

        return $pdf->stream($filename);
    }

    public function accept(Application $application)
    {
        $application->update([
            'status' => 'accepted'
        ]);

        return back()->with('success', 'Lamaran diterima');
    }

    public function reject(Application $application)
    {
        $application->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Lamaran ditolak');
    }

    public function byLowongan($lowonganId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);

        $applications = Application::with(['applicant.user', 'lowongan'])
            ->where('lowongan_id', $lowonganId)
            ->latest()
            ->get();

        return view('admin.applicants.index', compact('applications', 'lowongan'));
    }

    public function updateStage(Request $request)
    {
        // 1. Validasi semua kemungkinan input di awal (DI LUAR TRY)
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'next_status' => 'required',
            'rejection_reason' => 'required_if:next_status,rejected',
            'psikotes_date' => 'required_if:next_status,psikotes',
            'psikotes_end_date' => 'required_if:next_status,psikotes',
            'psikotes_link' => 'required_if:next_status,psikotes',
            'interview_date' => 'required_if:next_status,interview',
            'interview_type' => 'required_if:next_status,interview',
            'interview_link' => 'required_if:next_status,interview',
            'mcu_date' => 'required_if:next_status,mcu',
            'mcu_location_name' => 'required_if:next_status,mcu',
            'mcu_location_address' => 'required_if:next_status,mcu',
            'join_date' => 'required_if:next_status,accepted',
            'office_type' => 'required_if:next_status,accepted',
            'work_location' => 'required_if:next_status,accepted',
            'office_address' => 'required_if:next_status,accepted',
        ]);

        $application = Application::with(['applicant.user', 'lowongan'])->findOrFail($request->application_id);
        
        // 2. Update Database
        $updateData = [
            'status' => $request->next_status,
            'notes' => ($request->next_status === 'rejected') ? $request->rejection_reason : null
        ];
        $application->update($updateData);

        // 3. Logika Email dalam Try-Catch
        try {
            if ($request->next_status === 'psikotes') {
                Mail::to($application->applicant->user->email)
                    ->send(new \App\Mail\PsikotesEmail($application, $request->only(['psikotes_date', 'psikotes_end_date', 'psikotes_link', 'psikotes_token'])));

            } elseif ($request->next_status === 'interview') {
                if ($request->interview_type === 'lanjutan') {
                    Mail::to($application->applicant->user->email)
                        ->send(new \App\Mail\InterviewLanjutanEmail($application, $request->only(['interview_date', 'interview_link'])));
                } else {
                    Mail::to($application->applicant->user->email)
                        ->send(new \App\Mail\InterviewEmail($application, $request->only(['interview_date', 'interview_link'])));
                }

            } elseif ($request->next_status === 'offering') {
                Mail::to($application->applicant->user->email)
                    ->send(new \App\Mail\OfferingEmail($application));

            } elseif ($request->next_status === 'mcu') {
                Mail::to($application->applicant->user->email)
                    ->send(new \App\Mail\MCUEmail($application, $request->only(['mcu_date', 'mcu_location_name', 'mcu_location_address'])));
                    
            } elseif ($request->next_status === 'rejected') {
                Mail::to($application->applicant->user->email)
                    ->send(new \App\Mail\RejectedEmail($application));

            } elseif ($request->next_status === 'accepted') {
                Mail::to($application->applicant->user->email)
                    ->send(new \App\Mail\AcceptedEmail(
                        $application, 
                        $request->only(['join_date', 'work_location', 'office_address']),
                        $request->office_type
                    ));
            }

        } catch (\Exception $e) {
            // Menggunakan helper logger() agar tidak perlu import class Log
            logger()->error("Email Error: " . $e->getMessage());
            
            return redirect()->back()->with('success', 'Status berhasil diperbarui, namun email GAGAL dikirim. Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Status ' . $application->applicant->user->name . ' berhasil diperbarui dan email telah dikirim!');
    }

    public function bulkUpdate(Request $request) {
        $ids = $request->selected_ids; // Ini akan jadi array [1, 2, 3]
        $status = $request->status;

        Application::whereIn('id', $ids)->update(['status' => $status]);

        return back()->with('success', count($ids) . ' pelamar berhasil diupdate.');
    }
}
