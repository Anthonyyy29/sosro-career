<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Applicant;
use App\Models\ApplicantProfile;
use Barryvdh\DomPDF\Facade\Pdf; // Import DomPDF

class ProfileController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        // Jika sudah isi profil, langsung lempar ke show atau dashboard
        if ($user->applicant && $user->applicant->profile_completed) {
            return redirect()->route('applicant.profile.show');
        }
        return view('applicant.profile.create');
    }

    public function store(Request $request)
    {
        // 1️⃣ VALIDASI (Tambahkan field baru)
        $request->validate([
            'nik'              => 'required|digits:16',
            'jk'               => 'required|in:L,P',
            'tempat_lahir'     => 'required|string',
            'tanggal_lahir'    => 'required|date',
            'phone'            => 'required|string|regex:/^[0-9+\-\s]+$/|min:10|max:13',
            'alamat'           => 'required|string',
            'ex_employee'      => 'required|in:Ya,Tidak',
            'ex_company_name'  => 'nullable|required_if:ex_employee,Ya|string|max:255',
            'ex_last_position' => 'nullable|required_if:ex_employee,Ya|string|max:255',
            // Tambahan Validasi baru
            'expected_salary'  => 'required|string|max:255',
            'ready_dinas'      => 'required|in:Ya,Tidak',
            'ready_placed_out' => 'required|in:Ya,Tidak',
            'minat_ordered'    => 'required|array|min:13', 
            'perokok'          => 'required|in:Ya,Tidak',
            'bertato'          => 'required|in:Ya,Tidak',
            // Dokumen
            'doc_foto'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB
            'doc_cv'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
            'doc_ktp'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'doc_ijazah'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'doc_sim'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'doc_npwp'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'doc_bpjs_kes'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'doc_bpjs_tk'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', 
            'doc_lain'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'max' => 'File :attribute terlalu besar, maksimal :max KB.',
            'mimes' => 'Format file :attribute tidak valid, harus :values.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            // 2️⃣ PASTIKAN APPLICANT ADA
            $applicant = \App\Models\Applicant::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'status' => 'active',
                    'profile_completed' => true,
                ]
            );

            $realApplicantId = $applicant->id;

            // 3️⃣ HANDLE FILE UPLOAD -> applicant_documents
            $docs = ['doc_foto', 'doc_cv', 'doc_ktp', 'doc_ijazah', 'doc_sim', 'doc_npwp', 'doc_bpjs_kes', 'doc_bpjs_tk', 'doc_lain'];
            foreach ($docs as $doc) {
                if ($request->hasFile($doc)) {
                    $type = substr($doc, 4); // hapus prefix 'doc_'
                    $existing = $applicant->documents()->where('type', $type)->first();
                    if ($existing) {
                        Storage::disk('public')->delete($existing->file_path);
                        $existing->delete();
                    }
                    $applicant->documents()->create([
                        'type' => $type,
                        'file_path' => $request->file($doc)->store('applicant_docs', 'public'),
                    ]);
                }
            }

            // 4️⃣ MAPPING DATA
            $profileData = $request->only([
                'nik', 'jk', 'tempat_lahir', 'tanggal_lahir',
                'tinggi_badan', 'berat_badan', 'alamat', 'domisili', 'phone',
                'agama', 'status_nikah', 'instagram', 'linkedin',
                'ex_employee', 'ex_company_name', 'ex_last_position', 'penyakit',
                // Field Baru
                'expected_salary', 'expected_facilities', 'ready_dinas',
                'ready_placed_out', 'company_reference', 'perokok', 'bertato'
            ]);

            // Menangani Data Array/JSON
            $profileData['jenis_sim'] = $request->jenis_sim;
            $profileData['minat'] = $request->minat_ordered; // Menyimpan urutan dari SortableJS

            // 5️⃣ UPDATE ATAU CREATE PROFILE
            $profile = \App\Models\ApplicantProfile::updateOrCreate(
                ['applicant_id' => $realApplicantId],
                $profileData
            );

            // Helper: kolom date/integer tidak terima string kosong (beda dari JSON lama yang terima apa saja)
            $blankToNull = function (array $item, array $keys) {
                foreach ($keys as $key) {
                    if (($item[$key] ?? null) === '') {
                        $item[$key] = null;
                    }
                }
                return $item;
            };

            // 6️⃣ KELUARGA -> applicant_family_members (hapus semua, tulis ulang)
            $profile->familyMembers()->delete();
            foreach ($request->k_inti ?? [] as $item) {
                $profile->familyMembers()->create([...$blankToNull($item, ['tgl_lahir']), 'tipe' => 'inti']);
            }
            foreach ($request->k_kandung ?? [] as $item) {
                $profile->familyMembers()->create([...$blankToNull($item, ['tgl_lahir']), 'tipe' => 'kandung']);
            }

            // 7️⃣ PENGALAMAN KERJA -> applicant_work_experiences (hapus semua, tulis ulang)
            $profile->workExperiences()->delete();
            foreach ($request->pengalaman_kerja ?? [] as $item) {
                $profile->workExperiences()->create($blankToNull($item, ['tanggal_masuk', 'tanggal_keluar']));
            }

            // 8️⃣ PENDIDIKAN FORMAL & INFORMAL -> 2 tabel terpisah (hapus semua, tulis ulang)
            $profile->formalEducations()->delete();
            foreach ($request->pendidikan_formal ?? [] as $item) {
                $profile->formalEducations()->create($blankToNull($item, ['tahun_masuk', 'tahun_lulus']));
            }
            $profile->informalEducations()->delete();
            foreach ($request->pendidikan_informal ?? [] as $item) {
                $profile->informalEducations()->create($blankToNull($item, ['tahun']));
            }
        });

        return redirect()
            ->route('applicant.profile.show') // Saya arahkan ke show agar user bisa langsung lihat hasilnya
            ->with('success', 'Profil dan Dokumen berhasil disimpan.');
    }

    public function show()
    {
        $user = Auth::user();
        // Load relasi agar data di view muncul
        $applicant = Applicant::where('user_id', Auth::id())->with(['profile.familyMembers', 'profile.workExperiences', 'profile.formalEducations', 'profile.informalEducations', 'documents'])->first();

        if (!$applicant || !$applicant->profile) {
            return redirect()->route('applicant.profile.create');
        }

        return view('applicant.profile.show', compact('applicant'));
    }

    public function edit()
    {
        $user = Auth::user();
        $applicant = Applicant::where('user_id', Auth::id())->with(['profile.familyMembers', 'profile.workExperiences', 'profile.formalEducations', 'profile.informalEducations', 'documents'])->first();

        if (!$applicant || !$applicant->profile) {
            return redirect()->route('applicant.profile.create');
        }

        return view('applicant.profile.edit', compact('applicant'));
    }

    public function update(Request $request)
    {
        $this->store($request);

        return redirect()
            ->route('applicant.profile.show')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function downloadPdf()
    {
        $user = Auth::user();

        // 1. Cari dulu data Applicant yang terhubung dengan User ID ini
        $applicantData = \App\Models\Applicant::where('user_id', $user->id)->first();

        if (!$applicantData) {
            return back()->with('error', 'Data Applicant tidak ditemukan.');
        }

        // 2. Cari Profile menggunakan ID dari Applicant, bukan ID dari User
        $profile = \App\Models\ApplicantProfile::where('applicant_id', $applicantData->id)->first();

        if (!$profile) {
            // Pesan error ini muncul jika baris di applicant_profiles belum ada
            return back()->with('error', 'Data profil tidak ditemukan untuk ID Applicant: ' . $applicantData->id);
        }

        // 3. Gabungkan untuk dikirim ke View PDF
        $applicant = $user;
        $applicant->profile = $profile;
        $applicant->documents = $applicantData->documents;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('applicant.profile.pdf', compact('applicant'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream('BIODATA_' . strtoupper($user->name) . '.pdf');
    }
}