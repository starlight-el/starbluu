@extends('layouts.app')

@section('content')
<div class="container">

    <h3><a href="{{ route('landing') }}" class="text-dark text-decoration-none">&lt; World Tour</a></h3>

    <div class="row mt-3">
        @forelse ($tours as $tour)
            <div class="col-md-6 mb-3">
                <a href="{{ route('artist.show', ['id' => $tour->artist->id, 'from' => 'world-tour']) }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h5>{{ $tour->artist->nama_grup }}</h5>
                            <p class="mb-0">{{ $tour->nama_tour }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <p>Belum ada data world tour.</p>
        @endforelse
    </div>

</div>
@endsection