@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard Petugas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('officer.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">

        {{-- ===================================
             ROW 1 : STATISTIK UTAMA
        =================================== --}}
        <div class="row">

            {{-- KENDARAAN SEDANG PARKIR --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #0d6efd;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Sedang Parkir</h6>
                                <h2 class="fw-bold mb-0">{{ $vehiclesParked }}</h2>
                                <small class="text-muted">kendaraan</small>
                            </div>
                            <i class="bi bi-car-front" style="font-size: 2.5rem; color:#0d6efd;"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KENDARAAN MASUK HARI INI --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #20c997;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Masuk Hari Ini</h6>
                                <h2 class="fw-bold mb-0">{{ $vehiclesInToday }}</h2>
                                <small class="text-muted">kendaraan</small>
                            </div>
                            <i class="bi bi-door-open" style="font-size: 2.5rem; color:#20c997;"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KENDARAAN KELUAR HARI INI --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #dc3545;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Keluar Hari Ini</h6>
                                <h2 class="fw-bold mb-0">{{ $vehiclesOutToday }}</h2>
                                <small class="text-muted">kendaraan</small>
                            </div>
                            <i class="bi bi-door-closed" style="font-size: 2.5rem; color:#dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PEMBAYARAN PENDING --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #ffc107;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pembayaran Pending</h6>
                                <h2 class="fw-bold mb-0">{{ $paymentPending }}</h2>
                                <small class="text-muted">menunggu pembayaran</small>
                            </div>
                            <i class="bi bi-hourglass-split" style="font-size: 2.5rem; color:#ffc107;"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ===================================
             ROW 2 : SLOT PARKIR
        =================================== --}}
        <div class="row">

            {{-- SLOT TERPAKAI --}}
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm border-0" style="border-radius:12px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="bi bi-p-circle"></i> Status Slot Parkir</h5>

                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded"
                            style="background:#f8f9fa; border-left: 4px solid #0d6efd;">
                            <div>
                                <h6 class="fw-bold">Slot Terisi</h6>
                                <p class="text-muted mb-0">{{ $slotOccupied }} slot</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center p-3 rounded"
                            style="background:#f8f9fa; border-left: 4px solid #198754;">
                            <div>
                                <h6 class="fw-bold">Slot Kosong</h6>
                                <p class="text-muted mb-0">{{ $slotEmpty }} slot dari {{ $totalSlots }} total</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- KENDARAAN MASUK TERBARU --}}
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm border-0" style="border-radius:12px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="bi bi-clock-history"></i> Kendaraan Masuk Terbaru</h5>

                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse($recentIn as $item)
                                <div class="d-flex justify-content-between mb-3 p-3 rounded"
                                    style="background:#f8f9fa; border-left:4px solid #0d6efd;">
                                    <div>
                                        <div class="fw-bold">{{ $item->ticket_code }}</div>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($item->entry_time)->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <span class="badge bg-primary">IN</span>
                                </div>
                            @empty
                                <p class="text-center text-muted">Belum ada data</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

        </div>


        {{-- ===================================
             ROW 3 : KELUAR TERBARU
        =================================== --}}
        <div class="row">

            <div class="col-lg-12 mb-3">
                <div class="card shadow-sm border-0" style="border-radius:12px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="bi bi-receipt"></i> Kendaraan Keluar Terbaru</h5>

                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse($recentOut as $item)
                                <div class="d-flex justify-content-between mb-3 p-3 rounded"
                                    style="background:#f8f9fa; border-left:4px solid #198754;">
                                    <div>
                                        <div class="fw-bold">{{ $item->ticket_code }}</div>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($item->exit_time)->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <span class="badge bg-success">OUT</span>
                                </div>
                            @empty
                                <p class="text-center text-muted">Belum ada data</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection
