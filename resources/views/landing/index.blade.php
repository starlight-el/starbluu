@extends('layouts.app')

@section('content')
<div class="container">

    <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">

        <div class="carousel-indicators">
            @foreach ($tours as $tour)
                <button type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="{{ $loop->index }}"
                        class="{{ $loop->first ? 'active' : '' }}"
                        aria-label="Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>

        @php
            $bannerColors = ['#2c2c54', '#88c9a1', '#1e3d59', '#6b2737'];
        @endphp

        <div class="carousel-inner">
            @foreach ($tours as $tour)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <div class="banner-slide"
                        style="background-color: {{ $bannerColors[$loop->index % count($bannerColors)] }};
                                @if ($tour->foto_banner_home) background-image: url('{{ asset('storage/' . $tour->foto_banner_home) }}'); @endif">
                        <div class="carousel-caption">
                            <h4>{{ $tour->nama_tour }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>

    <h3><a href="{{ route('tour.index') }}" class="text-dark text-decoration-none">Tour &gt;</a></h3>
    <div class="row mb-4">
        @foreach ($tourList as $tour)
            <div class="col-md-6 mb-3">
                <a href="{{ route('artist.show', ['id' => $tour->artist->id, 'from' => 'landing']) }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h5>{{ $tour->artist->nama_grup }}</h5>
                            <p class="mb-0">{{ $tour->nama_tour }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <h3><a href="{{ route('tour.world') }}" class="text-dark text-decoration-none">World Tour &gt;</a></h3>
    <div class="row mb-4">
        @foreach ($worldTourList as $tour)
            <div class="col-md-6 mb-3">
                <a href="{{ route('artist.show', ['id' => $tour->artist->id, 'from' => 'landing']) }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h5>{{ $tour->artist->nama_grup }}</h5>
                            <p class="mb-0">{{ $tour->nama_tour }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

</div>

<style>
    .banner-slide {
        height: 320px;
        border-radius: 12px;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: flex-end;
    }
</style>
@endsection