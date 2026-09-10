@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container">

    <h3 class="page-title"><a href="{{ route('landing') }}" class="text-decoration-none">&lt; World Tour</a></h3>

    <div class="row mb-5">
        @forelse ($tours as $tour)
            <div class="col-md-4 col-sm-6 mb-4">
                <a href="{{ route('artist.show', ['id' => $tour->artist->id, 'from' => 'world-tour']) }}" class="text-decoration-none">
                    <div class="tour-card"
                        @if ($tour->artist->foto_thumbnail) style="background-image: url('{{ asset('storage/' . $tour->artist->foto_thumbnail) }}');" @endif>
                        @if ($tour->isOnTour())
                            <span class="on-tour-badge">ON TOUR</span>
                        @endif
                        <div class="tour-card-body">
                            <h5><span>{{ $tour->artist->nama_grup }}</span></h5>
                            <p>{{ $tour->nama_tour }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <p class="text-muted">Belum ada data world tour.</p>
        @endforelse
    </div>

</div>
@endsection