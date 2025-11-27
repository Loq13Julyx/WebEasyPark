@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Detail Data Parkir</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.parking-records.index') }}">Data Parkir</a>
                </li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        {{-- Header Card with Ticket Code --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center">
                            <div class="ticket-icon me-3">
                                <i class="bi bi-ticket-perforated fs-1 text-primary"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-1 small">Kode Tiket</p>
                                <h3 class="mb-0 fw-bold">{{ $record->ticket_code }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <div class="d-flex flex-column gap-2 align-items-lg-end">
                            <span class="badge {{ $record->status === 'in' ? 'bg-warning text-dark' : 'bg-success' }} px-3 py-2 fs-6">
                                <i class="bi bi-{{ $record->status === 'in' ? 'car-front' : 'check-circle' }} me-1"></i>
                                {{ $record->status === 'in' ? 'Masih Parkir' : 'Telah Keluar' }}
                            </span>
                            <span class="badge {{ $record->payment_status === 'paid' ? 'bg-primary' : 'bg-danger' }} px-3 py-2">
                                <i class="bi bi-{{ $record->payment_status === 'paid' ? 'check2-circle' : 'clock' }} me-1"></i>
                                {{ $record->payment_status === 'paid' ? 'Pembayaran Selesai' : 'Menunggu Pembayaran' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Card Informasi Detail --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Informasi Detail
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            {{-- Slot Parkir --}}
                            <div class="col-12">
                                <div class="info-box p-3 rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <div class="d-flex align-items-center text-white">
                                        <div class="icon-wrapper me-3">
                                            <i class="bi bi-geo-alt fs-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 opacity-75 small">Lokasi Parkir</p>
                                            <h4 class="mb-0 fw-bold">
                                                Slot {{ $record->parkingSlot->slot_code ?? $record->parkingSlot->id ?? '-' }}
                                            </h4>
                                            @if($record->parkingSlot && $record->parkingSlot->area)
                                                <p class="mb-0 small opacity-90">
                                                    <i class="bi bi-building"></i> {{ $record->parkingSlot->area->name }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tarif Tetap --}}
                            @php
                                $entry = \Carbon\Carbon::parse($record->entry_time);
                                $exit = $record->exit_time ? \Carbon\Carbon::parse($record->exit_time) : now();
                                $duration = $entry->diff($exit);
                                $totalHours = $duration->days * 24 + $duration->h;
                                $tarifRate = $record->tarif->rate ?? 0;
                            @endphp

                            <div class="col-12">
                                <div class="info-box p-3 rounded border border-2 border-success">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper me-3 bg-success bg-opacity-10 rounded-circle p-3">
                                            <i class="bi bi-cash fs-4 text-success"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1 small">Tarif Parkir (Harga Tetap)</p>
                                            <h4 class="mb-0 fw-bold text-success">
                                                Rp {{ number_format($tarifRate, 0, ',', '.') }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Vehicle Type --}}
                            @if($record->tarif && $record->tarif->vehicleType)
                            <div class="col-12">
                                <div class="info-box p-3 rounded bg-light">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper me-3">
                                            <i class="bi bi-car-front fs-3 text-secondary"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1 small">Tipe Kendaraan</p>
                                            <h6 class="mb-0 fw-bold">{{ $record->tarif->vehicleType->name }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Timeline --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            Timeline Parkir
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="timeline-custom">
                            {{-- Waktu Masuk --}}
                            <div class="timeline-item-custom mb-4">
                                <div class="d-flex">
                                    <div class="timeline-marker bg-primary">
                                        <i class="bi bi-box-arrow-in-right text-white"></i>
                                    </div>
                                    <div class="timeline-content ms-3 flex-grow-1">
                                        <div class="timeline-card p-3 rounded border-start border-primary border-4 bg-light">
                                            <p class="text-muted mb-1 small fw-semibold">WAKTU MASUK</p>
                                            <h5 class="mb-1 fw-bold">
                                                {{ \Carbon\Carbon::parse($record->entry_time)->format('d M Y') }}
                                            </h5>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock text-primary me-2"></i>
                                                <span class="fw-bold text-primary">
                                                    {{ \Carbon\Carbon::parse($record->entry_time)->format('H:i') }} WIB
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Durasi --}}
                            <div class="timeline-item-custom mb-4">
                                <div class="d-flex">
                                    <div class="timeline-marker bg-warning">
                                        <i class="bi bi-hourglass-split text-dark"></i>
                                    </div>
                                    <div class="timeline-content ms-3 flex-grow-1">
                                        <div class="timeline-card p-3 rounded border-start border-warning border-4 bg-light">
                                            <p class="text-muted mb-1 small fw-semibold">DURASI</p>
                                            <h5 class="mb-1 fw-bold">
                                                {{ $totalHours }} jam {{ $duration->i }} menit
                                            </h5>
                                            <p class="mb-0 small text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                {{ $record->exit_time ? 'Total durasi parkir' : 'Sedang berjalan...' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Waktu Keluar --}}
                            <div class="timeline-item-custom">
                                <div class="d-flex">
                                    <div class="timeline-marker bg-{{ $record->exit_time ? 'success' : 'secondary' }}">
                                        <i class="bi bi-box-arrow-right text-white"></i>
                                    </div>
                                    <div class="timeline-content ms-3 flex-grow-1">
                                        <div class="timeline-card p-3 rounded border-start border-{{ $record->exit_time ? 'success' : 'secondary' }} border-4 bg-light">
                                            <p class="text-muted mb-1 small fw-semibold">WAKTU KELUAR</p>
                                            @if ($record->exit_time)
                                                <h5 class="mb-1 fw-bold">
                                                    {{ \Carbon\Carbon::parse($record->exit_time)->format('d M Y') }}
                                                </h5>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-clock text-success me-2"></i>
                                                    <span class="fw-bold text-success">
                                                        {{ \Carbon\Carbon::parse($record->exit_time)->format('H:i') }} WIB
                                                    </span>
                                                </div>
                                            @else
                                                <p class="mb-0 fst-italic text-muted">
                                                    <i class="bi bi-dash-circle me-1"></i>
                                                    Kendaraan belum keluar
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <style>
        .card {
            border-radius: 12px;
        }

        .timeline-custom {
            position: relative;
        }
        
        .timeline-custom::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 50px;
            bottom: 50px;
            width: 3px;
            background: linear-gradient(to bottom, #0d6efd 0%, #ffc107 50%, #198754 100%);
            border-radius: 10px;
        }
        
        .timeline-marker {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 0 0 5px #fff, 0 4px 10px rgba(0,0,0,0.15);
            flex-shrink: 0;
        }
        
        .timeline-marker i {
            font-size: 1.1rem;
        }
        
        .timeline-card {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .info-box {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
    </style>
@endsection