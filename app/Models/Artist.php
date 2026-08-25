<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama_grup',
        'foto_thumbnail',
        'deskripsi',
    ];

    public function artistMembers()
    {
        return $this->hasMany(ArtistMember::class);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
}
