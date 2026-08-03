<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    // 1. Simpan pesan dari form depan (kontak.blade.php)
    public function store(Request $request)
    {
        if ($request->filled('confirm_email_address')) {
            return redirect()->back(); // Honeypot trap
        }

        $validated = $request->validate([
            'confirm_email_address' => 'prohibited',
            'name' => 'required|string|max:100',
            'email' => 'required|email:rfc,dns',
            'city' => 'required',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'email.email' => 'Format email tidak valid',
            'message.min' => 'Pesan minimal 10 karakter',
            'message.max' => 'Pesan maksimal 2000 karakter',
        ]);

        Contact::create($validated);

        return redirect()->back()->with('success_modal', 'Pesan Anda telah berhasil kami terima. Tim kami akan segera menindaklanjuti.');
    }

    // 2. Dashboard Admin (admin/kontak/index.blade.php)
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            // Superadmin melihat semua pesan
            $messages = Contact::with('admin')->latest()->paginate(10);
            $branchAdmins = Admin::where('role', 'admin')->get();
            return view('admin.kontak.index', compact('messages', 'branchAdmins'));
        } else {
            // Admin Cabang hanya melihat yang ditugaskan ke mereka
            $messages = Contact::where('admin_id', $user->id)->latest()->paginate(10);
            return view('admin.kontak.index', compact('messages'));
        }
    }

    // 3. Superadmin menugaskan ke Admin Cabang
    public function assign(Request $request, Contact $contact)
    {
        $request->validate(['admin_id' => 'required|exists:admins,id']);

        $contact->update([
            'admin_id' => $request->admin_id,
            'status' => 'forwarded'
        ]);

        return back()->with('success', 'Pesan berhasil diteruskan ke Admin Cabang.');
    }

    public function destroy(Contact $contact)
    {
        // Proteksi tambahan: Pastikan hanya superadmin yang bisa hapus
        if (Auth::user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang dapat menghapus pesan.');
        }

        $contact->delete();

        return back()->with('success', 'Pesan berhasil dihapus dari sistem.');
    }
        public function markAsReplied(Contact $contact)
    {
        // Update status menjadi replied dan catat siapa yang membalas
        $contact->update([
            'status' => 'replied',
            'admin_id' => $contact->admin_id ?? Auth::id() // Jika belum ada admin_id, isi dengan yang sedang login
        ]);

        return back(); // Kembali dengan cepat
    }
}