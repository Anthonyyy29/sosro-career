<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function edit()
    {
        $applicant = Applicant::where('user_id', Auth::id())->with('documents')->first();

        if (! $applicant || ! $applicant->profile) {
            return redirect()->route('applicant.profile.create');
        }

        return view('applicant.profile.documents', compact('applicant'));
    }

    public function update(Request $request)
    {
        $applicant = Applicant::where('user_id', Auth::id())->with('documents')->first();

        if (! $applicant || ! $applicant->profile) {
            return redirect()->route('applicant.profile.create');
        }

        // Dokumen wajib cuma dipaksa kalau belum pernah diunggah -- yang sudah ada
        // tidak perlu diunggah ulang tiap kali halaman ini disubmit.
        $existingTypes = $applicant->documents->pluck('type')->all();
        $definitions = Applicant::documentDefinitions();

        $rules = [];
        $attributes = [];
        foreach ($definitions as $type => $def) {
            $mustUpload = $def['required'] && ! in_array($type, $existingTypes, true);

            $rules['doc_'.$type] = [
                Rule::requiredIf($mustUpload),
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:'.($def['limit'] * 1024),
            ];
            $attributes['doc_'.$type] = $def['label'];
        }

        $request->validate($rules, [
            'required' => 'Dokumen :attribute wajib diunggah.',
            'max' => 'File :attribute terlalu besar, maksimal :max KB.',
            'mimes' => 'Format file :attribute tidak valid, harus :values.',
        ], $attributes);

        DB::transaction(function () use ($request, $applicant, $definitions) {
            foreach (array_keys($definitions) as $type) {
                $field = 'doc_'.$type;

                if (! $request->hasFile($field)) {
                    continue;
                }

                // Ganti file lama dengan tipe yang sama, jangan numpuk
                $existing = $applicant->documents()->where('type', $type)->first();
                if ($existing) {
                    Storage::disk('public')->delete($existing->file_path);
                    $existing->delete();
                }

                $applicant->documents()->create([
                    'type' => $type,
                    'file_path' => $request->file($field)->store('applicant_docs', 'public'),
                ]);
            }

            $applicant->recalculateDocumentsCompleted();
        });

        return redirect()
            ->route('applicant.dashboard')
            ->with('success', 'Dokumen berhasil diunggah.');
    }
}
