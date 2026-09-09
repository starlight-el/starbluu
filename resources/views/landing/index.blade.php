@extends('layouts.app')

@section('body-class', 'starbluu-theme')

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

        <div class="carousel-inner">
            @foreach ($tours as $tour)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <div class="banner-slide"
                        @if ($tour->foto_banner_home) style="background-image: url('{{ asset('storage/' . $tour->foto_banner_home) }}');" @endif>
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

    <h3 class="section-title"><a href="{{ route('tour.index') }}" class="text-decoration-none">Tour &gt;</a></h3>
    <div class="row mb-5">
        @foreach ($tourList as $tour)
            <div class="col-md-4 col-sm-6 mb-4">
                <a href="{{ route('artist.show', ['id' => $tour->artist->id, 'from' => 'landing']) }}" class="text-decoration-none">
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
        @endforeach
    </div>

    <h3 class="section-title"><a href="{{ route('tour.world') }}" class="text-decoration-none">World Tour &gt;</a></h3>
    <div class="row mb-5">
        @foreach ($worldTourList as $tour)
            <div class="col-md-4 col-sm-6 mb-4">
                <a href="{{ route('artist.show', ['id' => $tour->artist->id, 'from' => 'landing']) }}" class="text-decoration-none">
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
        @endforeach
    </div>

</div>
@endsection
