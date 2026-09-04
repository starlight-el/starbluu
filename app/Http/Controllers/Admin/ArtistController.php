<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\ArtistMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::withCount('artistMembers')->orderBy('nama_grup')->get();

        return view('admin.artist.index', compact('artists'));
    }

    public function create()
    {
        return view('admin.artist.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_grup' => 'required|string|max:255',
            'foto_thumbnail' => 'required|image|max:2048',
            'deskripsi' => 'nullable|string',
            'members' => 'required|array|min:1',
            'members.*.nama_member' => 'required|string|max:255',
            'members.*.foto_member' => 'required|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $fotoThumbnailPath = $request->file('foto_thumbnail')->store('artists', 'public');

            $artist = Artist::create([
                'nama_grup' => $request->nama_grup,
                'foto_thumbnail' => $fotoThumbnailPath,
                'deskripsi' => $request->deskripsi,
            ]);

            foreach ($request->members as $member) {
                $fotoMemberPath = $member['foto_member']->store('artist_members', 'public');

                ArtistMember::create([
                    'artist_id' => $artist->id,
                    'nama_member' => $member['nama_member'],
                    'foto_member' => $fotoMemberPath,
                ]);
            }
        });

        return redirect()->route('admin.artists.index')->with('info', 'Data Artist berhasil ditambahkan.');
    }

    public function edit(Artist $artist)
    {
        $artist->load('artistMembers');

        return view('admin.artist.edit', compact('artist'));
    }

    public function update(Request $request, Artist $artist)
    {
        $request->validate([
            'nama_grup' => 'required|string|max:255',
            'foto_thumbnail' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
            'members' => 'required|array|min:1',
            'members.*.id' => 'nullable|exists:artist_members,id',
            'members.*.nama_member' => 'required|string|max:255',
            'members.*.foto_member' => 'nullable|image|max:2048',
            'deleted_members' => 'nullable|array',
            'deleted_members.*' => 'exists:artist_members,id',
        ]);

        DB::transaction(function () use ($request, $artist) {
            $fotoThumbnailPath = $artist->foto_thumbnail;

            if ($request->hasFile('foto_thumbnail')) {
                if ($artist->foto_thumbnail) {
                    Storage::disk('public')->delete($artist->foto_thumbnail);
                }

                $fotoThumbnailPath = $request->file('foto_thumbnail')->store('artists', 'public');
            }

            $artist->update([
                'nama_grup' => $request->nama_grup,
                'foto_thumbnail' => $fotoThumbnailPath,
                'deskripsi' => $request->deskripsi,
            ]);

            foreach ($request->input('deleted_members', []) as $deletedId) {
                $member = ArtistMember::find($deletedId);

                if ($member) {
                    if ($member->foto_member) {
                        Storage::disk('public')->delete($member->foto_member);
                    }

                    $member->delete();
                }
            }

            foreach ($request->members as $index => $member) {
                $fotoMemberPath = null;

                if (isset($member['foto_member'])) {
                    $fotoMemberPath = $request->file("members.$index.foto_member")->store('artist_members', 'public');
                }

                if (!empty($member['id'])) {
                    $existingMember = ArtistMember::find($member['id']);

                    $updateData = ['nama_member' => $member['nama_member']];

                    if ($fotoMemberPath) {
                        if ($existingMember->foto_member) {
                            Storage::disk('public')->delete($existingMember->foto_member);
                        }
                        $updateData['foto_member'] = $fotoMemberPath;
                    }

                    $existingMember->update($updateData);
                } else {
                    ArtistMember::create([
                        'artist_id' => $artist->id,
                        'nama_member' => $member['nama_member'],
                        'foto_member' => $fotoMemberPath,
                    ]);
                }
            }
        });

        return redirect()->route('admin.artists.index')->with('info', 'Data Artist berhasil diubah.');
    }

    public function destroy(Artist $artist)
    {
        DB::transaction(function () use ($artist) {
            if ($artist->foto_thumbnail) {
                Storage::disk('public')->delete($artist->foto_thumbnail);
            }

            foreach ($artist->artistMembers as $member) {
                if ($member->foto_member) {
                    Storage::disk('public')->delete($member->foto_member);
                }
            }

            $artist->delete();
        });

        return redirect()->route('admin.artists.index')->with('info', 'Data Artist berhasil dihapus.');
    }
}