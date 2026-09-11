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

    <div class="jadwal-list">
        @forelse ($tour->jadwals ?? [] as $jadwal)
            @php
                $totalKuota = $jadwal->ticketTiers->sum('kuota');
            @endphp
            <div class="jadwal-row">
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
    </div>

</div>
@endsection