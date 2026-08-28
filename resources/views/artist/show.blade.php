@extends('layouts.app')

@section('content')
<div class="container">

    @php
        $backUrl = match(request('from')) {
            'tour' => route('tour.index'),
            'world-tour' => route('tour.world'),
            default => route('landing'),
        };
    @endphp
    <a href="{{ $backUrl }}">&lt; Kembali</a>

    <div class="row mt-3">
        <div class="col-md-8">
            <h2>{{ $artist->nama_grup }}</h2>
            <p>{{ $artist->deskripsi }}</p>
        </div>
    </div>

    <h4 class="mt-4">Members</h4>
    <div class="row">
        @foreach ($artist->artistMembers as $member)
            <div class="col-md-3 text-center mb-3">
                <p>{{ $member->nama_member }}</p>
            </div>
        @endforeach
    </div>

    <h4 class="mt-4">Tour Dates and Tickets</h4>

    @foreach ($artist->tours as $tour)
        <h5>{{ $tour->nama_tour }}</h5>

        @foreach ($tour->jadwals as $jadwal)
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1">{{ $jadwal->kota }} - {{ $jadwal->venue }}</p>
                        <p class="mb-0 text-muted">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }}
                            @if ($jadwal->jam)
                                {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }} {{ $jadwal->timezone }}
                            @endif
                        </p>
                    </div>

                    @php
                        $totalKuota = $jadwal->ticketTiers->sum('kuota');
                    @endphp

                    @if ($totalKuota > 0)
                        <a href="{{ route('tickettier.show', ['jadwalId' => $jadwal->id, 'from' => request('from')]) }}" class="btn btn-dark">GET TICKETS</a>
                    @else
                        <button class="btn btn-secondary" disabled>SOLD OUT</button>
                    @endif
                </div>
            </div>
        @endforeach
    @endforeach

</div>
@endsection