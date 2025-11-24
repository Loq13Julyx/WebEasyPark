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

        {{-- ROW 1: STATISTIK UTAMA --}}
        <div class="row">

            {{-- KENDARAAN SEDANG PARKIR --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #ff6b6b;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Sedang Parkir</h6>
                                <h2 class="fw-bold mb-0">{{ $kendaraanSedangParkir }}</h2>
                                <small class="text-muted">kendaraan</small>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-car-front" style="font-size: 2.5rem; color: #ff6b6b;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SLOT TERSEDIA --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #28a745;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Slot Tersedia</h6>
                                <h2 class="fw-bold mb-0">{{ $slotKosong }}</h2>
                                <small class="text-muted">dari {{ $totalSlots }} slot</small>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-p-square" style="font-size: 2.5rem; color: #28a745;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PENDAPATAN HARI INI --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #0d6efd;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pendapatan Hari Ini</h6>
                                <h2 class="fw-bold mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h2>
                                <small class="text-muted">{{ $kendaraanKeluarHariIni }} transaksi</small>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-cash-coin" style="font-size: 2.5rem; color: #0d6efd;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PENDAPATAN BULAN INI --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #6f42c1;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pendapatan Bulan Ini</h6>
                                <h2 class="fw-bold mb-0">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h2>
                                <small class="text-muted">{{ now()->format('F Y') }}</small>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-graph-up-arrow" style="font-size: 2.5rem; color: #6f42c1;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ROW 2: INFO TAMBAHAN --}}
        <div class="row">

            {{-- OCCUPANCY --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #fd7e14;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Tingkat Occupancy</h6>
                                <h2 class="fw-bold mb-0">{{ $persentaseOccupancy }}%</h2>
                                <small class="text-muted">
                                    {{ $persentaseOccupancy > 80 ? 'Hampir Penuh' : ($persentaseOccupancy > 50 ? 'Sedang' : 'Tersedia') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-speedometer2" style="font-size: 2.5rem; color: #fd7e14;"></i>
                            </div>
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
                                <h2 class="fw-bold mb-0">{{ $kendaraanMasukHariIni }}</h2>
                                <small class="text-muted"><i class="bi bi-arrow-down-circle"></i> Kendaraan</small>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-car-front-fill" style="font-size: 2.5rem; color: #20c997;"></i>
                            </div>
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
                                <h2 class="fw-bold mb-0">{{ $pembayaranPending }}</h2>
                                <small class="text-muted"><i class="bi bi-clock-history"></i> Menunggu</small>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-hourglass-split" style="font-size: 2.5rem; color: #ffc107;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RATA-RATA DURASI PARKIR --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow border-0" style="border-left:6px solid #0dcaf0;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Rata-rata Durasi Parkir</h6>
                                <h2 class="fw-bold mb-0">{{ $avgDurationFormatted }} jam</h2>
                                <small class="text-muted"><i class="bi bi-clock"></i> Hari Ini</small>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-stopwatch" style="font-size: 2.5rem; color: #0dcaf0;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ROW 3: GRAFIK --}}
        <div class="row mt-3">

            {{-- GRAFIK PENDAPATAN 6 BULAN (FULL WIDTH) --}}
            <div class="col-lg-12 mb-3">
                <div class="card shadow-sm border-0" style="border-radius:12px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-graph-up"></i> Pendapatan 6 Bulan Terakhir
                        </h5>
                        <canvas id="earningChart" height="80"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- ROW 4: TABEL DATA --}}
        <div class="row">

            {{-- STATUS AREA PARKIR --}}
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm border-0" style="border-radius:12px;">
                    <div class="card-body py-4">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-geo-alt"></i> Status Area Parkir
                        </h5>

                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse($areaStats as $area)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded"
                                    style="background:#f8f9fa; border-left: 4px solid {{ $area->occupancy_percentage > 80 ? '#dc3545' : '#0d6efd' }};">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $area->name }}</h6>
                                        <div class="text-muted small d-flex justify-content-between">
                                            <span>Terisi: {{ $area->occupied }}</span>
                                            <span>Tersedia: {{ $area->available }}</span>
                                            <span>Total: {{ $area->total_slots }}</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold">{{ $area->occupancy_percentage }}%</span>
                                        <div class="progress mt-2" style="height: 6px; width: 100px;">
                                            <div class="progress-bar {{ $area->occupancy_percentage > 80 ? 'bg-danger' : 'bg-primary' }}"
                                                style="width: {{ $area->occupancy_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">Belum ada data area parkir</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

            {{-- TRANSAKSI TERBARU --}}
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm border-0" style="border-radius:12px;">
                    <div class="card-body py-4">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-receipt"></i> Transaksi Terbaru
                        </h5>

                        {{-- Container scrollable --}}
                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse($lastPayments as $payment)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded"
                                    style="background:#f8f9fa; border-left: 4px solid #0d6efd;">
                                    <div>
                                        <div class="fw-bold">{{ $payment->ticket_code }}</div>
                                        <div class="text-muted" style="font-size: 12px;">
                                            <i class="bi bi-calendar3"></i>
                                            {{ \Carbon\Carbon::parse($payment->exit_time)->format('d M Y, H:i') }}
                                        </div>
                                        <div class="text-muted" style="font-size: 12px;">
                                            <i class="bi bi-tag"></i> {{ $payment->tarif->vehicleType->name ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-primary d-block">
                                            Rp {{ number_format($payment->tarif->rate, 0, ',', '.') }}
                                        </span>
                                        <span class="badge bg-success" style="font-size: 10px;">Paid</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">Belum ada transaksi</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </section>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // =====================
        // GRAFIK PENDAPATAN
        // =====================
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
                    pointRadius: 5,
                    pointBackgroundColor: "#0d6efd",
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
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => "Rp " + value.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    </script>
@endsection
