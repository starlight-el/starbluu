<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'ticket_tier_id',
        'jumlah_tiket',
        'total_harga',
        'status',
        'metode_pembayaran',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function ticketTier()
    {
        return $this->belongsTo(TicketTier::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
