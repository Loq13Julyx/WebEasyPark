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
