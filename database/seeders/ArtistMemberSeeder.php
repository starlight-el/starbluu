<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;
use App\Models\ArtistMember;

class ArtistMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aespa = Artist::where('nama_grup', 'aespa')->first();

        ArtistMember::create([
            'artist_id' => $aespa->id,
            'nama_member' => 'Karina',
            'foto_member' => 'karina.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $aespa->id,
            'nama_member' => 'Winter',
            'foto_member' => 'winter.jpg',
        ]);

        ArtistMember::create([
            'artist_id' => $aespa->id,
            'nama_member' => 'Giselle',
            'foto_member' => 'giselle.jpg',
        ]);

        ArtistMember::create([
            'artist_id' => $aespa->id,
            'nama_member' => 'Ningning',
            'foto_member' => 'ningning.jpg',
        ]);

        $cortis = Artist::where('nama_grup', 'CORTIS')->first();

        ArtistMember::create([
            'artist_id' => $cortis->id,
            'nama_member' => 'Martin',
            'foto_member' => 'martin.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $cortis->id,
            'nama_member' => 'James',
            'foto_member' => 'james.jpg',
        ]);

        ArtistMember::create([
            'artist_id' => $cortis->id,
            'nama_member' => 'Juhoon',
            'foto_member' => 'juhoon.jpg',
        ]);

        ArtistMember::create([
            'artist_id' => $cortis->id,
            'nama_member' => 'Seonghyeon',
            'foto_member' => 'seonghyeon.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $cortis->id,
            'nama_member' => 'Keonho',
            'foto_member' => 'keonho.jpg',
        ]);
        
        $straykids = Artist::where('nama_grup', 'Stray Kids')->first();

        ArtistMember::create([
            'artist_id' => $straykids->id,
            'nama_member' => 'Bangchan',
            'foto_member' => 'bangchan.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $straykids->id,
            'nama_member' => 'Lee Know',
            'foto_member' => 'leeknow.jpg',
        ]);

        ArtistMember::create([
            'artist_id' => $straykids->id,
            'nama_member' => 'Changbin',
            'foto_member' => 'changbin.jpg',
        ]);

        ArtistMember::create([
            'artist_id' => $straykids->id,
            'nama_member' => 'Hyunjin',
            'foto_member' => 'hyunjin.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $straykids->id,
            'nama_member' => 'Han',
            'foto_member' => 'han.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $straykids->id,
            'nama_member' => 'Felix',
            'foto_member' => 'felix.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $straykids->id,
            'nama_member' => 'Seungmin',
            'foto_member' => 'seungmin.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $straykids->id,
            'nama_member' => 'I.N',
            'foto_member' => 'i.n.jpg',
        ]);

        $babymonster = Artist::where('nama_grup', 'BABYMONSTER')->first();

        ArtistMember::create([
            'artist_id' => $babymonster->id,
            'nama_member' => 'Ruka',
            'foto_member' => 'ruka.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $babymonster->id,
            'nama_member' => 'Pharita',
            'foto_member' => 'pharita.jpg',
        ]);

        ArtistMember::create([
            'artist_id' => $babymonster->id,
            'nama_member' => 'Asa',
            'foto_member' => 'asa.jpg',
        ]);

        ArtistMember::create([
            'artist_id' => $babymonster->id,
            'nama_member' => 'Ahyeon',
            'foto_member' => 'ahyeon.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $babymonster->id,
            'nama_member' => 'Rami',
            'foto_member' => 'rami.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $babymonster->id,
            'nama_member' => 'Rora',
            'foto_member' => 'rora.jpg',
        ]);
        
        ArtistMember::create([
            'artist_id' => $babymonster->id,
            'nama_member' => 'Chiquita',
            'foto_member' => 'chiquita.jpg',
        ]);
        
    }
}
