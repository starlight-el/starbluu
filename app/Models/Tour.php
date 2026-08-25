<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'artist_id',
        'nama_tour',
        'kategori',
        'foto_banner_home',
        'foto_banner_detail',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
    
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

}
