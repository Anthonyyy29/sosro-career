<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'lowongan_id',
        'status',
        'notes',
    ];

    public function user()
    {
        // Karena Application tidak punya user_id secara langsung, jadi ambil User melalui Applicant
        return $this->hasOneThrough(
            User::class, 
            Applicant::class, 
            'id',           // Local key di Applicant
            'id',           // Local key di User
            'applicant_id', // Foreign key di Application
            'user_id'       // Foreign key di Applicant
        );
    }
    
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(\App\Models\Admin\Lowongan::class, 'lowongan_id');
    }

    // Keikutsertaan lamaran ini dalam kelompok "Konfirmasi User".
    public function konfirmasiItems()
    {
        return $this->hasMany(UserConfirmationItem::class, 'application_id');
    }

    /*
     * Keadaan lamaran ini di tahap "Konfirmasi User", dibaca dari kelompok terbaru:
     *   null          -> tidak sedang ikut kelompok mana pun
     *   'menunggu'    -> kelompoknya belum diputuskan (inilah "unconfirmed")
     *   'terpilih'    -> lamaran ini yang dipilih (inilah "confirmed")
     *   'tidak'       -> kelompoknya sudah diputuskan, tapi bukan lamaran ini
     */
    public function keadaanKonfirmasi(): ?string
    {
        $item = $this->konfirmasiItems->sortByDesc('id')->first();

        if (! $item || ! $item->confirmation) {
            return null;
        }

        if (! $item->confirmation->sudahDipilih()) {
            return 'menunggu';
        }

        return $item->confirmation->selected_application_id === $this->id ? 'terpilih' : 'tidak';
    }

    // Kelompok konfirmasi terbaru yang memuat lamaran ini.
    public function konfirmasiTerbaru(): ?UserConfirmation
    {
        return $this->konfirmasiItems->sortByDesc('id')->first()?->confirmation;
    }
}
