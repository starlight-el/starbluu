@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container">

    @php
        $tour = $artist->tours->first();
        $backUrl = match(request('from')) {
            'tour' => route('tour.index'),
            'world-tour' => route('tour.world'),
            default => route('landing'),
        };

        $jadwals = $tour
            ? $tour->jadwals->sortBy(fn ($j) => $j->tanggal . ' ' . $j->jam)->values()
            : collect();

        $jumlahAvailable = $jadwals->filter(fn ($j) => $j->ticketTiers->sum('kuota') > 0)->count();
        $jumlahSoldOut = $jadwals->count() - $jumlahAvailable;
        $tabDefault = $jumlahAvailable > 0 ? 'available' : 'all';
    @endphp

    <a href="{{ $backUrl }}" class="back-link">&lt; Kembali</a>

    <div class="row artist-hero">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="artist-banner"
                @if ($tour && $tour->foto_banner_detail) style="background-image: url('{{ asset('storage/' . $tour->foto_banner_detail) }}');" @endif>
                @if ($tour)
                    <div class="artist-banner-caption">
                        <h1>{{ $tour->nama_tour }}</h1>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="artist-info-box">
                <h2>{{ $artist->nama_grup }}</h2>
                <p>{{ $artist->deskripsi }}</p>
            </div>

            <h4 class="tour-subtitle">Members</h4>
            <div class="row member-list">
                @forelse ($artist->artistMembers as $member)
                    <div class="col-4 col-md-3 member-item">
                        <div class="member-avatar"
                            @if ($member->foto_member) style="background-image: url('{{ asset('storage/' . $member->foto_member) }}');" @endif>
                            @unless ($member->foto_member)
                                {{ strtoupper(substr($member->nama_member, 0, 1)) }}
                            @endunless
                        </div>
                        <p>{{ $member->nama_member }}</p>
                    </div>
                @empty
                    <p class="text-muted">Belum ada data member.</p>
                @endforelse
            </div>
        </div>
    </div>

    <h4 class="tour-dates-title mt-5">Tour Dates and Tickets</h4>

    @if ($jadwals->isNotEmpty())
        <div class="jadwal-tabs" role="tablist">
            <button type="button" class="jadwal-tab {{ $tabDefault === 'all' ? 'active' : '' }}" data-filter="all" role="tab" aria-selected="{{ $tabDefault === 'all' ? 'true' : 'false' }}">
                All <span class="jadwal-tab-count">({{ $jadwals->count() }})</span>
            </button>
            <button type="button" class="jadwal-tab {{ $tabDefault === 'available' ? 'active' : '' }}" data-filter="available" role="tab" aria-selected="{{ $tabDefault === 'available' ? 'true' : 'false' }}">
                Available <span class="jadwal-tab-count">({{ $jumlahAvailable }})</span>
            </button>
            <button type="button" class="jadwal-tab" data-filter="soldout" role="tab" aria-selected="false">
                Sold Out <span class="jadwal-tab-count">({{ $jumlahSoldOut }})</span>
            </button>
        </div>
    @endif

    <div class="jadwal-list">
        @forelse ($jadwals as $jadwal)
            @php
                $totalKuota = $jadwal->ticketTiers->sum('kuota');
                $status = $totalKuota > 0 ? 'available' : 'soldout';
            @endphp
            <div class="jadwal-row" data-status="{{ $status }}" @if ($tabDefault !== 'all' && $tabDefault !== $status) hidden @endif>
                <div class="jadwal-kota">{{ $jadwal->kota }}</div>
                <div class="jadwal-venue">{{ $jadwal->venue }}</div>
                <div class="jadwal-date">
                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }}
                    @if ($jadwal->jam)
                        {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }} {{ $jadwal->timezone }}
                    @endif
                </div>
                <div class="jadwal-action">
                    @if ($totalKuota > 0)
                        <a href="{{ route('tickettier.show', ['jadwalId' => $jadwal->id, 'from' => request('from')]) }}" class="btn-accent">GET TICKETS</a>
                    @else
                        <span class="btn-soldout">SOLD OUT</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada jadwal tour.</p>
        @endforelse

        @if ($jadwals->isNotEmpty())
            <p class="jadwal-empty" id="jadwal-empty" hidden></p>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.jadwal-tab');
    const rows = document.querySelectorAll('.jadwal-row');
    const emptyEl = document.getElementById('jadwal-empty');

    if (!tabs.length) {
        return;
    }

    const pesanKosong = {
        available: 'Semua jadwal tour ini sudah sold out.',
        soldout: 'Belum ada jadwal yang sold out.',
        all: 'Belum ada jadwal tour.',
    };

    function terapkanFilter(filter) {
        let tampil = 0;

        rows.forEach(function (row) {
            const cocok = filter === 'all' || row.dataset.status === filter;
            row.hidden = !cocok;

            if (cocok) {
                tampil++;
            }
        });

        emptyEl.textContent = pesanKosong[filter];
        emptyEl.hidden = tampil > 0;
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });

            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');

            terapkanFilter(tab.dataset.filter);
        });
    });

    terapkanFilter(document.querySelector('.jadwal-tab.active').dataset.filter);
});
</script>
@endpush