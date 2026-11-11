<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

trait ResolvesCheckoutRedirect
{
    /**
     * Ambil alamat tujuan setelah login/register, hanya jika berupa
     * halaman Pilih Tier (/jadwal/{id}/tiket). Selain itu diabaikan.
     */
    protected function tujuanKembali(Request $request): ?string
    {
        $tujuan = $request->input('redirect');

        if (is_string($tujuan) && preg_match('#^/jadwal/\d+/tiket(\?[A-Za-z0-9_=&%.\-]*)?$#', $tujuan)) {
            return $tujuan;
        }

        return null;
    }
}