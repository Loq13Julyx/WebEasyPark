@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard Admin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">

        {{-- ROW 1: STATISTIK KEUANGAN UTAMA --}}
        <div class="row">

            {{-- PENDAPATAN HARI INI --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #0d6efd;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Pendapatan Hari Ini</h6>
                                <h3 class="fw-bold mb-0 text-primary">Rp
                                    {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                                <small class="text-muted">
                                    <i class="bi bi-receipt"></i> {{ $kendaraanKeluarHariIni }} transaksi
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-cash-coin" style="font-size: 2rem; color: #0d6efd;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PENDAPATAN BULAN INI --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #6f42c1;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Pendapatan Bulan Ini</h6>
                                <h3 class="fw-bold mb-0" style="color: #6f42c1;">Rp
                                    {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> {{ now()->format('F Y') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="rounded-circle p-3" style="background: rgba(111, 66, 193, 0.1);">
                                    <i class="bi bi-graph-up-arrow" style="font-size: 2rem; color: #6f42c1;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KENDARAAN MASUK HARI INI --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #20c997;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Kendaraan Masuk</h6>
                                <h3 class="fw-bold mb-0" style="color: #20c997;">{{ $kendaraanMasukHariIni }}</h3>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-day"></i> Hari ini
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-arrow-down-circle-fill" style="font-size: 2rem; color: #20c997;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SLOT TERSEDIA --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #28a745;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Slot Tersedia</h6>
                                <h3 class="fw-bold mb-0 text-success">{{ $slotKosong }}/{{ $totalSlots }}</h3>
                                <small class="text-muted">
                                    <i class="bi bi-p-square"></i> slot parkir
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-p-circle" style="font-size: 2rem; color: #28a745;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ROW 2: GRAFIK PENDAPATAN --}}
        <div class="row mt-2">
            <div class="col-12 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-graph-up text-primary"></i>
                                Grafik Pendapatan 6 Bulan Terakhir
                            </h5>
                            <span class="badge bg-primary">Analitik</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="earningChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: DATA DETAIL --}}
        <div class="row">

            {{-- STATUS AREA PARKIR --}}
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header py-3"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px 12px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-white fw-bold">
                                <i class="bi bi-geo-alt-fill"></i> Status Area Parkir
                            </h5>
                            <span class="badge bg-white text-dark">{{ count($areaStats) }} Area</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div style="max-height: 400px; overflow-y: auto;">
                            @forelse($areaStats as $area)
                                <div class="mb-3 p-3 rounded position-relative overflow-hidden"
                                    style="background: linear-gradient(135deg, {{ $area->occupancy_percentage > 80 ? 'rgba(220, 53, 69, 0.08)' : 'rgba(13, 110, 253, 0.08)' }} 0%, #ffffff 100%); 
                                   border-left: 4px solid {{ $area->occupancy_percentage > 80 ? '#dc3545' : '#0d6efd' }};">

                                    {{-- Header --}}
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="fw-bold mb-0" style="font-size: 15px;">
                                                <i
                                                    class="bi bi-building-fill {{ $area->occupancy_percentage > 80 ? 'text-danger' : 'text-primary' }}"></i>
                                                {{ $area->name }}
                                            </h6>
                                        </div>
                                        <div>
                                            <span
                                                class="badge {{ $area->occupancy_percentage > 80 ? 'bg-danger' : ($area->occupancy_percentage > 50 ? 'bg-warning' : 'bg-success') }}"
                                                style="font-size: 14px; padding: 6px 12px;">
                                                {{ $area->occupancy_percentage }}%
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Stats --}}
                                    <div class="row g-2 mb-3">
                                        <div class="col-4">
                                            <div class="text-center p-2 rounded"
                                                style="background: rgba(40, 167, 69, 0.1);">
                                                <div class="text-success" style="font-size: 11px; font-weight: 600;">
                                                    <i class="bi bi-check-circle-fill"></i> TERSEDIA
                                                </div>
                                                <div class="fw-bold text-success" style="font-size: 18px;">
                                                    {{ $area->available }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center p-2 rounded"
                                                style="background: rgba(220, 53, 69, 0.1);">
                                                <div class="text-danger" style="font-size: 11px; font-weight: 600;">
                                                    <i class="bi bi-x-circle-fill"></i> TERISI
                                                </div>
                                                <div class="fw-bold text-danger" style="font-size: 18px;">
                                                    {{ $area->occupied }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center p-2 rounded"
                                                style="background: rgba(108, 117, 125, 0.1);">
                                                <div class="text-secondary" style="font-size: 11px; font-weight: 600;">
                                                    <i class="bi bi-grid-3x3-gap-fill"></i> TOTAL
                                                </div>
                                                <div class="fw-bold text-secondary" style="font-size: 18px;">
                                                    {{ $area->total_slots }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted">Occupancy Rate</small>
                                            <small
                                                class="fw-bold {{ $area->occupancy_percentage > 80 ? 'text-danger' : 'text-primary' }}">
                                                {{ $area->occupancy_percentage > 80 ? '⚠️ Hampir Penuh' : ($area->occupancy_percentage > 50 ? '📊 Sedang' : '✅ Tersedia') }}
                                            </small>
                                        </div>
                                        <div class="progress" style="height: 10px; border-radius: 10px;">
                                            <div class="progress-bar {{ $area->occupancy_percentage > 80 ? 'bg-danger' : ($area->occupancy_percentage > 50 ? 'bg-warning' : 'bg-success') }}"
                                                style="width: {{ $area->occupancy_percentage }}%; 
                                                border-radius: 10px;
                                                box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
                                    </div>
                                    <h6 class="text-muted">Belum ada data area parkir</h6>
                                    <p class="text-muted small mb-0">Area parkir akan ditampilkan di sini</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- TRANSAKSI TERBARU --}}
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header py-3"
                        style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px 12px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-white fw-bold">
                                <i class="bi bi-receipt-cutoff"></i> Transaksi Terbaru
                            </h5>
                            <span class="badge bg-white text-dark">10 Terakhir</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div style="max-height: 400px; overflow-y: auto;">
                            @forelse($lastPayments as $payment)
                                <div class="mb-3 p-3 rounded position-relative overflow-hidden"
                                    style="background: linear-gradient(135deg, rgba(17, 153, 142, 0.08) 0%, #ffffff 100%); 
                                   border-left: 4px solid #11998e;">

                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            {{-- Ticket Code --}}
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bg-primary bg-opacity-10 rounded px-2 py-1 me-2">
                                                    <i class="bi bi-ticket-perforated-fill text-primary"></i>
                                                </div>
                                                <div class="fw-bold text-primary" style="font-size: 15px;">
                                                    {{ $payment->ticket_code }}
                                                </div>
                                            </div>

                                            {{-- Info --}}
                                            <div class="ms-1">
                                                <div class="mb-1">
                                                    <small class="text-muted d-block" style="font-size: 12px;">
                                                        <i class="bi bi-calendar-check-fill text-success"></i>
                                                        <strong>Exit:</strong>
                                                        {{ \Carbon\Carbon::parse($payment->exit_time)->format('d M Y, H:i') }}
                                                    </small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-secondary bg-opacity-75"
                                                        style="font-size: 11px;">
                                                        <i class="bi bi-tag-fill"></i>
                                                        {{ $payment->tarif->vehicleType->name ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Amount & Status --}}
                                        <div class="text-end ms-3">
                                            <div class="mb-2">
                                                <div class="fw-bold" style="font-size: 18px; color: #11998e;">
                                                    Rp {{ number_format($payment->tarif->rate, 0, ',', '.') }}
                                                </div>
                                            </div>
                                            <span class="badge bg-success" style="padding: 6px 12px;">
                                                <i class="bi bi-check-circle-fill"></i> Lunas
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-receipt" style="font-size: 4rem; color: #dee2e6;"></i>
                                    </div>
                                    <h6 class="text-muted">Belum ada transaksi hari ini</h6>
                                    <p class="text-muted small mb-0">Transaksi akan ditampilkan di sini</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // GRAFIK PENDAPATAN
        const earningCtx = document.getElementById('earningChart').getContext('2d');
        new Chart(earningCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthLabels) !!},
                datasets: [{
                    label: "Pendapatan (Rp)",
                    data: {!! json_encode($monthlyEarnings) !!},
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: "#0d6efd",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2,
                    backgroundColor: "rgba(13, 110, 253, 0.1)",
                    borderColor: "#0d6efd",
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => "Rp " + value.toLocaleString('id-ID'),
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
