<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Admin\Lowongan;
use App\Models\RecruitmentStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
        // Validasi input. Daftar kolom wajib per tahap tidak ditulis di sini lagi,
        // melainkan diambil dari kunci 'fields' di config/recruitment.php.
        $request->validate(array_merge([
            'application_id' => 'required|exists:applications,id',
            'next_status' => ['required', Rule::in(RecruitmentStage::allKeys())],
        ], RecruitmentStage::fieldRules()), [
            'required_if' => 'Kolom :attribute wajib diisi untuk tahapan ini.',
        ]);

        $application = Application::with(['applicant.user', 'lowongan'])->findOrFail($request->application_id);

        // KEAMANAN: Cegah update status jika bukan di cabangnya
        if (Auth::user()->role !== 'superadmin' && $application->lowongan->cabang_id !== Auth::user()->cabang_id) {
            abort(403, 'Akses ditolak.');
        }

        // Tahap harus berlaku untuk kategori lowongan lamaran ini. Sebelumnya
        // validasi cuma memeriksa "kunci tahap ini ada", jadi tahap milik kategori
        // lain (mis. 'simulasi' pada lamaran Profesional) tetap lolos lewat POST
        // langsung, meski opsinya tidak pernah muncul di dropdown.
        $kategori = $application->lowongan->kategori;

        if (! in_array($request->next_status, RecruitmentStage::selectableFor($kategori), true)) {
            return back()->withErrors([
                'next_status' => 'Tahap "'.$request->next_status.'" tidak berlaku untuk kategori '.$kategori.'.',
            ]);
        }

        
        $updateData = [
            'status' => $request->next_status,
            'notes' => ($request->next_status === 'rejected') ? $request->rejection_reason : null
        ];
        $application->update($updateData);

        try {
            $userEmail = $application->applicant->user->email;

            // Kelas email tiap tahap ditentukan di config/recruitment.php.
            // Tahap yang tidak punya baris 'mail' di sana memang sengaja tidak
            // mengirim apa pun ke pelamar -- jadi tidak perlu cabang khusus.
            $tahap = $request->next_status;

            if ($kelasEmail = RecruitmentStage::mailClass($tahap, $request->all())) {
                Mail::to($userEmail)->send(
                    new $kelasEmail($application, RecruitmentStage::mailData($tahap, $request->all()))
                );
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
    // Menyaring lamaran ke cabang admin yang sedang login. Superadmin lihat semua.
    // Dipakai semua jalur massal supaya penjagaannya tidak bisa terlewat di salah satu.
    private function lingkupCabang($query)
    {
        if (Auth::user()->role !== 'superadmin') {
            $query->whereHas('lowongan', function ($q) {
                $q->where('cabang_id', Auth::user()->cabang_id);
            });
        }

        return $query;
    }

    // Update massal untuk tahap yang tidak butuh isian tambahan.
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'status' => ['required', Rule::in(RecruitmentStage::bulkUpdateStages())],
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer',
        ]);

        $jumlah = $this->lingkupCabang(Application::whereIn('id', $request->selected_ids))
            ->update(['status' => $request->status]);

        return back()->with('success', $jumlah . ' pelamar berhasil diperbarui.');
    }

    // Tahap yang butuh isian per pelamar (psikotes, interview) menampilkan layar
    // persiapan dulu. Halaman mana yang dipakai ditentukan kunci 'bulk_form' di
    // config/recruitment.php -- tidak lagi ditulis sebagai if di sini.
    public function bulkPrepare(Request $request)
    {
        $request->validate([
            'status' => ['required', Rule::in(RecruitmentStage::bulkUpdateStages())],
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer',
        ]);

        $status = $request->status;
        $halaman = config("recruitment.stages.{$status}.bulk_form");

        if (! $halaman) {
            return $this->bulkUpdate($request);
        }

        $applications = $this->lingkupCabang(
            Application::with(['applicant.user', 'lowongan'])->whereIn('id', $request->selected_ids)
        )->get();

        return view($halaman, compact('applications', 'status'));
    }

    // Memproses layar persiapan di atas. Satu method untuk semua tahap: kelas email
    // dan isinya diambil dari config lewat resolver yang sama dengan alur satuan
    // (updateStage), jadi kedua jalur tidak bisa lagi menyimpang satu sama lain.
    public function bulkProcess(Request $request)
    {
        $request->validate([
            'status' => ['required', Rule::in(RecruitmentStage::bulkUpdateStages())],
            'applicants' => 'required|array|min:1',
        ]);

        $status = $request->status;
        $isianPerPelamar = $request->input('applicants');

        $applications = $this->lingkupCabang(
            Application::with(['applicant.user', 'lowongan'])->whereIn('id', array_keys($isianPerPelamar))
        )->get();

        foreach ($applications as $application) {
            $isian = $isianPerPelamar[$application->id] ?? [];

            $application->update(['status' => $status]);

            try {
                if ($kelasEmail = RecruitmentStage::mailClass($status, $isian)) {
                    Mail::to($application->applicant->user->email)->send(
                        new $kelasEmail($application, RecruitmentStage::mailData($status, $isian))
                    );
                }
            } catch (\Exception $e) {
                logger()->error("Gagal kirim email massal ke lamaran {$application->id}: " . $e->getMessage());
            }
        }

        $label = RecruitmentStage::labels()[$status] ?? $status;

        return redirect()->route('admin.applicants')
            ->with('success', $applications->count() . ' pelamar berhasil dipindahkan ke tahap ' . $label . '.');
    }

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