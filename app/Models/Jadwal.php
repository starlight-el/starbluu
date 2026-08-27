<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tour_id',
        'negara',
        'kota',
        'venue',
        'tanggal',
        'jam',
        'timezone',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
    
    public function ticketTiers()
    {
        return $this->hasMany(TicketTier::class);
    }

}
