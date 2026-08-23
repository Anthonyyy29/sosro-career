<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Support\Facades\Auth;

/*
 * Penyaringan data ke cabang admin yang sedang login.
 *
 * Dijadikan trait supaya jalur mana pun yang menyentuh lamaran memakai penjagaan
 * yang sama persis. Kalau disalin per controller, cepat atau lambat ada satu
 * salinan yang ketinggalan diperbarui -- dan itu sudah pernah terjadi di sini
 * (jalur Update Massal dulu tidak punya penjagaan ini sama sekali).
 */
trait ScopesToCabang
{
    protected function lingkupCabang($query)
    {
        if (Auth::user()->role !== 'superadmin') {
            $query->whereHas('lowongan', function ($q) {
                $q->where('cabang_id', Auth::user()->cabang_id);
            });
        }

        return $query;
    }
}
