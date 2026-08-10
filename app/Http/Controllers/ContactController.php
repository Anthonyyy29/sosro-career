<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Admin;
use App\Models\Cabang;
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
            $messages = Contact::with(['admin', 'cabang'])->latest()->paginate(10);
            $cabangs = Cabang::orderBy('kelompok')->orderBy('nama')->get();
            return view('admin.kontak.index', compact('messages', 'cabangs'));
        } else {
            // Admin Cabang melihat semua pesan yang ditugaskan ke cabangnya (bukan cuma ke dirinya)
            $messages = Contact::with(['admin', 'cabang'])
                ->where('cabang_id', $user->cabang_id)
                ->latest()->paginate(10);
            return view('admin.kontak.index', compact('messages'));
        }
    }

    // 3. Superadmin menugaskan ke Cabang -- semua admin di cabang itu bisa lihat & balas
    public function assign(Request $request, Contact $contact)
    {
        $request->validate(['cabang_id' => 'required|exists:cabangs,id']);

        $contact->update([
            'cabang_id' => $request->cabang_id,
            'status' => 'forwarded'
        ]);

        return back()->with('success', 'Pesan berhasil diteruskan ke Cabang.');
    }

    public function destroy(Contact $contact)
    {
        // Superadmin boleh hapus semua; admin cabang cuma boleh hapus pesan yang ditugaskan ke cabangnya
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $contact->cabang_id !== $user->cabang_id) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesan ini.');
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