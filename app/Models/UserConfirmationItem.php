<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * Satu kandidat di dalam kelompok konfirmasi, berikut catatan interview yang
 * ditulis admin dan ditampilkan ke user sebagai bahan pertimbangan.
 */
class UserConfirmationItem extends Model
{
    protected $fillable = ['user_confirmation_id', 'application_id', 'catatan_interview'];

    public function confirmation()
    {
        return $this->belongsTo(UserConfirmation::class, 'user_confirmation_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
