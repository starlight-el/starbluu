@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Tour & World Tour</h2>

    <a href="{{ route('tour.index') }}">Semua</a>
    <a href="{{ route('tour.index', ['kategori' => 'tour']) }}">Tour</a>
    <a href="{{ route('tour.index', ['kategori' => 'world_tour']) }}">World Tour</a>

    <div class="row mt-3">
        @forelse ($tours as $tour)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ $tour->nama_tour }}</h5>
                        <p>{{ $tour->artist->nama_grup }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada data tour</p>
        @endforelse
    </div>
</div>
@endsection