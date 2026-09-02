<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'kode_eticket',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function generateKodeETicket(string $namaArtist)
    {
        $kodeArtist = strtoupper(str_replace(' ', '', $namaArtist));

        $jumlahTiketArtistIni = self::whereHas('order.ticketTier.jadwal.tour.artist', function ($query) use ($namaArtist) {
            $query->where('nama_grup', $namaArtist);
        })->count();

        $nomorUrut = str_pad($jumlahTiketArtistIni + 1, 6, '0', STR_PAD_LEFT);

        return 'SB-' . $kodeArtist . '-' . $nomorUrut;
    }
}