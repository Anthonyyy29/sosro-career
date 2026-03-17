<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Admin $admin */
    $admin = Auth::user();

    if (!$admin || $admin->role !== 'superadmin') {
        abort(403, 'Maaf, halaman ini hanya untuk Super Admin.');
    }
        // Ambil data dari tabel admins
        $admins = Admin::select('id', 'name', 'email', 'role', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->source_table = 'admins'; // Penanda asal tabel
                return $item;
            });

        // Ambil data dari tabel users (Pelamar)
        $users = User::select('id', 'name', 'email', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->role = 'pelamar'; // Kita beri label manual
                $item->source_table = 'users';
                return $item;
            });

        // Gabungkan keduanya
        $allUsers = $admins->concat($users)->sortByDesc('created_at');

        return view('admin.users.index', compact('allUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email|unique:admins,email',
            'password' => 'required|min:8',
            'role'     => 'required|in:superadmin,admin,pelamar',
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ];

        if ($request->role === 'pelamar') {
            // Masuk ke tabel users
            User::create($data);
            $message = "Akun Pelamar berhasil dibuat.";
        } else {
            // Masuk ke tabel admins (admin cabang atau superadmin)
            $data['role'] = $request->role;
            Admin::create($data);
            $message = "Akun " . ucfirst($request->role) . " berhasil dibuat.";
        }

        return back()->with('success', $message);
    }

    public function destroy($id, Request $request)
    {
        // 1. Ambil identitas Admin yang sedang login
        $currentAdminId = \Illuminate\Support\Facades\Auth::id();
        $source = $request->query('source');

        // 2. CEK: Apakah akun yang mau dihapus adalah akun sendiri?
        // Jika ID target sama dengan ID login DAN berasal dari tabel admins
        if ($id == $currentAdminId && $source === 'admins') {
            return back()->with('error', 'Tindakan Ditolak: Anda tidak diperbolehkan menghapus akun Anda sendiri demi keamanan sistem.');
        }

        // 3. Jika lolos pengecekan, lanjutkan penghapusan berdasarkan tabel asal
        try {
            if ($source === 'admins') {
                $userToDelete = \App\Models\Admin::findOrFail($id);
            } else {
                $userToDelete = \App\Models\User::findOrFail($id);
            }

            $userToDelete->delete();
            return back()->with('success', 'Pengguna berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}