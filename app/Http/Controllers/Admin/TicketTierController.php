<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\TicketTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketTierController extends Controller
{
    private array $namaTierOptions = [
        'VIP Soundcheck',
        'Floor/Standing',
        'CAT 1',
        'CAT 2',
        'CAT 3',
    ];

    public function index()
    {
        $jadwals = Jadwal::with('tour.artist')->withCount('ticketTiers')->orderBy('tanggal')->get();

        return view('admin.tickettier.index', compact('jadwals'));
    }

    public function create(Request $request)
    {
        $jadwals = Jadwal::with('tour.artist')
            ->withCount('ticketTiers')
            ->orderBy('tanggal')
            ->get()
            ->where('ticket_tiers_count', 0);

        $selectedJadwalId = $request->query('jadwal_id');

        return view('admin.tickettier.create', compact('jadwals', 'selectedJadwalId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'tiers' => 'required|array|min:1',
            'tiers.*.nama_tier' => 'required|in:' . implode(',', $this->namaTierOptions),
            'tiers.*.harga' => 'required|numeric|min:0',
            'tiers.*.kuota' => 'required|integer|min:0',
        ]);

        $namaTierList = collect($request->tiers)->pluck('nama_tier');

        if ($namaTierList->duplicates()->isNotEmpty()) {
            return back()->withErrors(['tiers' => 'Tidak boleh ada Nama Tier yang sama dalam 1 kali submit.'])->withInput();
        }

        $sudahAda = TicketTier::where('jadwal_id', $request->jadwal_id)
            ->whereIn('nama_tier', $namaTierList)
            ->exists();

        if ($sudahAda) {
            return back()->withErrors(['tiers' => 'Salah satu Nama Tier yang dipilih sudah ada untuk Jadwal ini.'])->withInput();
        }

        DB::transaction(function () use ($request) {
            foreach ($request->tiers as $tier) {
                TicketTier::create([
                    'jadwal_id' => $request->jadwal_id,
                    'nama_tier' => $tier['nama_tier'],
                    'harga' => $tier['harga'],
                    'kuota' => $tier['kuota'],
                ]);
            }
        });

        return redirect()->route('admin.tickettiers.index')->with('info', 'Data Ticket Tier berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $jadwal->load(['tour.artist', 'ticketTiers']);

        return view('admin.tickettier.edit', compact('jadwal'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'tiers' => 'required|array|min:1',
            'tiers.*.id' => 'nullable|exists:ticket_tiers,id',
            'tiers.*.nama_tier' => 'required|in:' . implode(',', $this->namaTierOptions),
            'tiers.*.harga' => 'required|numeric|min:0',
            'tiers.*.kuota' => 'required|integer|min:0',
            'deleted_tiers' => 'nullable|array',
            'deleted_tiers.*' => 'exists:ticket_tiers,id',
        ]);

        $namaTierList = collect($request->tiers)->pluck('nama_tier');

        if ($namaTierList->duplicates()->isNotEmpty()) {
            return back()->withErrors(['tiers' => 'Tidak boleh ada Nama Tier yang sama dalam 1 kali submit.'])->withInput();
        }

        $idsDalamRequest = collect($request->tiers)->pluck('id')->filter()->all();

        $konflik = TicketTier::where('jadwal_id', $jadwal->id)
            ->whereIn('nama_tier', $namaTierList)
            ->whereNotIn('id', $idsDalamRequest)
            ->exists();

        if ($konflik) {
            return back()->withErrors(['tiers' => 'Salah satu Nama Tier yang dipilih sudah dipakai tier lain di Jadwal ini.'])->withInput();
        }

        DB::transaction(function () use ($request, $jadwal) {
            foreach ($request->input('deleted_tiers', []) as $deletedId) {
                $tier = TicketTier::find($deletedId);

                if ($tier) {
                    $tier->delete();
                }
            }

            foreach ($request->tiers as $tierData) {
                if (!empty($tierData['id'])) {
                    $tier = TicketTier::find($tierData['id']);

                    $tier->update([
                        'nama_tier' => $tierData['nama_tier'],
                        'harga' => $tierData['harga'],
                        'kuota' => $tierData['kuota'],
                    ]);
                } else {
                    TicketTier::create([
                        'jadwal_id' => $jadwal->id,
                        'nama_tier' => $tierData['nama_tier'],
                        'harga' => $tierData['harga'],
                        'kuota' => $tierData['kuota'],
                    ]);
                }
            }
        });

        return redirect()->route('admin.tickettiers.index')->with('info', 'Data Ticket Tier berhasil diubah.');
    }

    public function destroy(Jadwal $jadwal)
    {
        DB::transaction(function () use ($jadwal) {
            $jadwal->ticketTiers()->delete();
        });

        return redirect()->route('admin.tickettiers.index')->with('info', 'Semua Ticket Tier untuk jadwal ini berhasil dihapus.');
    }
}