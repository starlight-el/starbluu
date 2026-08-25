<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistMember extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'artist_id',
        'nama_member',
        'foto_member',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
