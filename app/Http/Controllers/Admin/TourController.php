<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Tour;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::with('artist')->withCount('jadwals')->get()
            ->sortBy(function ($tour) {
                return $tour->kategori . '-' . $tour->artist->nama_grup;
            })
            ->values();

        return view('admin.tour.index', compact('tours'));
    }

    public function create()
    {
        $artists = Artist::doesntHave('tours')->orderBy('nama_grup')->get();

        return view('admin.tour.create', compact('artists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'artist_id' => [
                'required',
                'exists:artists,id',
                function ($attribute, $value, $fail) {
                    if (Tour::where('artist_id', $value)->exists()) {
                        $fail('Artist ini sudah punya Tour. Gunakan Edit untuk menambah Jadwal.');
                    }
                },
            ],
            'nama_tour' => 'required|string|max:255',
            'kategori' => 'required|in:tour,world_tour',
            'foto_banner_home' => 'required|image|max:2048',
            'foto_banner_detail' => 'required|image|max:2048',
            'jadwals' => 'required|array|min:1',
            'jadwals.*.negara' => 'required|string|max:255',
            'jadwals.*.kota' => 'required|string|max:255',
            'jadwals.*.venue' => 'required|string|max:255',
            'jadwals.*.tanggal' => 'required|date',
            'jadwals.*.jam' => 'nullable|date_format:H:i',
            'jadwals.*.timezone' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($request) {
            $fotoBannerHomePath = $request->file('foto_banner_home')->store('tours', 'public');
            $fotoBannerDetailPath = $request->file('foto_banner_detail')->store('tours', 'public');

            $tour = Tour::create([
                'artist_id' => $request->artist_id,
                'nama_tour' => $request->nama_tour,
                'kategori' => $request->kategori,
                'foto_banner_home' => $fotoBannerHomePath,
                'foto_banner_detail' => $fotoBannerDetailPath,
            ]);

            foreach ($request->jadwals as $jadwal) {
                Jadwal::create([
                    'tour_id' => $tour->id,
                    'negara' => $jadwal['negara'],
                    'kota' => $jadwal['kota'],
                    'venue' => $jadwal['venue'],
                    'tanggal' => $jadwal['tanggal'],
                    'jam' => $jadwal['jam'] ?? null,
                    'timezone' => $jadwal['timezone'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.tours.index')->with('info', 'Data Tour & Jadwal berhasil ditambahkan.');
    }

    public function edit(Tour $tour)
    {
        $tour->load('jadwals');

        $artists = Artist::where(function ($query) use ($tour) {
            $query->doesntHave('tours')->orWhere('id', $tour->artist_id);
        })->orderBy('nama_grup')->get();

        return view('admin.tour.edit', compact('tour', 'artists'));
    }

    public function update(Request $request, Tour $tour)
    {
        $request->validate([
            'artist_id' => [
                'required',
                'exists:artists,id',
                function ($attribute, $value, $fail) use ($tour) {
                    $sudahDipakaiTourLain = Tour::where('artist_id', $value)->where('id', '!=', $tour->id)->exists();

                    if ($sudahDipakaiTourLain) {
                        $fail('Artist ini sudah punya Tour lain.');
                    }
                },
            ],
            'nama_tour' => 'required|string|max:255',
            'kategori' => 'required|in:tour,world_tour',
            'foto_banner_home' => 'nullable|image|max:2048',
            'foto_banner_detail' => 'nullable|image|max:2048',
            'jadwals' => 'required|array|min:1',
            'jadwals.*.id' => 'nullable|exists:jadwals,id',
            'jadwals.*.negara' => 'required|string|max:255',
            'jadwals.*.kota' => 'required|string|max:255',
            'jadwals.*.venue' => 'required|string|max:255',
            'jadwals.*.tanggal' => 'required|date',
            'jadwals.*.jam' => 'nullable|date_format:H:i',
            'jadwals.*.timezone' => 'nullable|string|max:50',
            'deleted_jadwals' => 'nullable|array',
            'deleted_jadwals.*' => 'exists:jadwals,id',
        ]);

        DB::transaction(function () use ($request, $tour) {
            $fotoBannerHomePath = $tour->foto_banner_home;
            $fotoBannerDetailPath = $tour->foto_banner_detail;

            if ($request->hasFile('foto_banner_home')) {
                if ($tour->foto_banner_home) {
                    Storage::disk('public')->delete($tour->foto_banner_home);
                }
                $fotoBannerHomePath = $request->file('foto_banner_home')->store('tours', 'public');
            }

            if ($request->hasFile('foto_banner_detail')) {
                if ($tour->foto_banner_detail) {
                    Storage::disk('public')->delete($tour->foto_banner_detail);
                }
                $fotoBannerDetailPath = $request->file('foto_banner_detail')->store('tours', 'public');
            }

            $tour->update([
                'artist_id' => $request->artist_id,
                'nama_tour' => $request->nama_tour,
                'kategori' => $request->kategori,
                'foto_banner_home' => $fotoBannerHomePath,
                'foto_banner_detail' => $fotoBannerDetailPath,
            ]);

            foreach ($request->input('deleted_jadwals', []) as $deletedId) {
                $jadwal = Jadwal::find($deletedId);

                if ($jadwal) {
                    $jadwal->delete();
                }
            }

            foreach ($request->jadwals as $jadwalData) {
                if (!empty($jadwalData['id'])) {
                    $jadwal = Jadwal::find($jadwalData['id']);

                    $jadwal->update([
                        'negara' => $jadwalData['negara'],
                        'kota' => $jadwalData['kota'],
                        'venue' => $jadwalData['venue'],
                        'tanggal' => $jadwalData['tanggal'],
                        'jam' => $jadwalData['jam'] ?? null,
                        'timezone' => $jadwalData['timezone'] ?? null,
                    ]);
                } else {
                    Jadwal::create([
                        'tour_id' => $tour->id,
                        'negara' => $jadwalData['negara'],
                        'kota' => $jadwalData['kota'],
                        'venue' => $jadwalData['venue'],
                        'tanggal' => $jadwalData['tanggal'],
                        'jam' => $jadwalData['jam'] ?? null,
                        'timezone' => $jadwalData['timezone'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('admin.tours.index')->with('info', 'Data Tour & Jadwal berhasil diubah.');
    }

    public function destroy(Tour $tour)
    {
        DB::transaction(function () use ($tour) {
            if ($tour->foto_banner_home) {
                Storage::disk('public')->delete($tour->foto_banner_home);
            }

            if ($tour->foto_banner_detail) {
                Storage::disk('public')->delete($tour->foto_banner_detail);
            }

            $tour->delete();
        });

        return redirect()->route('admin.tours.index')->with('info', 'Data Tour & Jadwal berhasil dihapus.');
    }
}
