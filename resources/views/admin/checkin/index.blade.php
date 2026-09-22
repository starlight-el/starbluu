@extends('layouts.admin')

@section('content')
    <div class="admin-container checkin-page-container">

        <div class="admin-page-header flex-shrink-0 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Validasi Check-In Tiket</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Masukkan kode e-ticket untuk memvalidasi kehadiran.</p>
            </div>
        </div>

        <div class="checkin-main-grid">
            
            <div class="checkin-left-col">
                <div class="admin-form-card checkin-form-card flex-shrink-0">
                    <form method="POST" action="{{ route('admin.checkin.store') }}" class="d-flex align-items-end gap-3">
                        @csrf
                        <div class="flex-grow-1">
                            <label class="form-label text-muted">Kode E-Ticket</label>
                            <input
                                type="text"
                                name="kode_eticket"
                                id="kodeEticket"
                                class="form-control @error('kode_eticket') is-invalid @enderror"
                                value="{{ old('kode_eticket') }}"
                            >
                        </div>
                        <div>
                            <button type="submit" class="admin-btn-solid" style="height: 44px;">Validasi</button>
                        </div>
                    </form>
                </div>

                <div class="dash-table-card checkin-history-card">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.95rem;">Riwayat Check-In</h6>
                    <div class="checkin-history-list">
                        @forelse ($riwayat as $tiket)
                            @php
                                $jadwal = $tiket->order->ticketTier->jadwal;
                                $artist = $jadwal->tour->artist;
                            @endphp
                            <div class="checkin-history-item">
                                <div class="checkin-history-left">
                                    <div class="admin-initial-avatar">
                                        {{ strtoupper(substr($artist->nama_grup, 0, 1)) }}
                                    </div>
                                    <div class="checkin-history-meta">
                                        <div class="checkin-history-name">{{ $tiket->order->ticketTier->nama_tier }} &mdash; {{ $tiket->order->user->name }}</div>
                                    </div>
                                </div>
                                <div class="checkin-history-right">
                                    <div class="checkin-history-artist">{{ $artist->nama_grup }}</div>
                                    <div class="checkin-history-time">{{ $tiket->checked_in_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <div class="mb-2" style="font-size: 2rem;">📋</div>
                                Belum ada riwayat check-in.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="checkin-right-col @if (session('checkin_result') && !session('checkin_result')['valid']) is-compact @endif">
                @if (session('checkin_result'))
                    @php $result = session('checkin_result'); @endphp
                    <div class="checkin-result-card {{ $result['valid'] ? 'is-valid' : 'is-invalid' }}">
                        <div class="checkin-result-header">
                            <div class="checkin-icon-badge {{ $result['valid'] ? 'icon-check' : 'icon-cross' }}"></div>
                            <div class="checkin-result-title">
                                {{ $result['valid'] ? 'Tiket Valid' : 'Tiket Tidak Valid' }}
                            </div>
                        </div>

                        @if ($result['valid'])
                            <div class="admin-detail-rows checkin-result-rows mb-0">
                                <div class="admin-detail-row">
                                    <span class="admin-detail-label">Tier</span>
                                    <span class="admin-detail-value">{{ $result['nama_tier'] }}</span>
                                </div>
                                <div class="admin-detail-row">
                                    <span class="admin-detail-label">Atas Nama</span>
                                    <span class="admin-detail-value">{{ $result['nama_customer'] }}</span>
                                </div>
                                <div class="admin-detail-row">
                                    <span class="admin-detail-label">Artist / Tour</span>
                                    <span class="admin-detail-value">{{ $result['nama_artist'] }} &mdash; {{ $result['nama_tour'] }}</span>
                                </div>
                                <div class="admin-detail-row">
                                    <span class="admin-detail-label">Lokasi</span>
                                    <span class="admin-detail-value">{{ $result['kota'] }}, {{ $result['venue'] }}</span>
                                </div>
                                <div class="admin-detail-row">
                                    <span class="admin-detail-label">Jadwal</span>
                                    <span class="admin-detail-value">
                                        {{ $result['tanggal'] }}@if ($result['jam']), {{ $result['jam'] }}@endif @if ($result['timezone']){{ $result['timezone'] }}@endif
                                    </span>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mt-3 mb-0">{{ $result['message'] }}</p>
                        @endif
                    </div>
                @endif
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('kodeEticket');
            if (input) {
                input.value = '';
            }

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{!! $errors->first() !!}',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'bluu-swal-popup',
                        title: 'bluu-swal-title',
                        htmlContainer: 'bluu-swal-text',
                        confirmButton: 'bluu-swal-confirm',
                    },
                });
            @endif
        });
    </script>
@endpush