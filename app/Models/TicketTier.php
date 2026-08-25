<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTier extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'jadwal_id',
        'nama_tier',
        'harga',
        'kuota',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
