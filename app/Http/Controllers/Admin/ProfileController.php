<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Menampilkan halaman profil admin
    public function index()
    {
        return view('admin.profile.index');
    }

    // Menangani update foto profil
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        /** @var \App\Models\Admin $user */
        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists('photos/' . $user->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('photos/' . $user->photo);
            }

            // Simpan file baru
            $file = $request->file('photo');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('photos', $filename, 'public');

            // Update dan Simpan
            $user->photo = $filename;
            $user->save(); // Sekarang metode save() akan dikenali
        }

        return redirect()->back()->with('success', 'Foto profil diperbarui!');
    }
}