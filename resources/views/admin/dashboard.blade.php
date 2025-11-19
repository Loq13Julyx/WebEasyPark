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

<section class="section">

    {{-- Welcome Box --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius:12px;">
                <div class="card-body py-4">
                    <h4 class="fw-bold mb-1">Selamat Datang, {{ $user->name }} 👋</h4>
                    <p class="text-muted mb-0">
                        Kelola area parkir dengan profesional dan tetap semangat!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- SLOT TERISI --}}
        <div class="col-lg-4 col-12 mb-3">
            <div class="card shadow border-0" style="border-left:6px solid #dc3545;">
                <div class="card-body py-4">
                    <h6 class="text-muted">Slot Terisi</h6>
                    <h2 class="fw-bold">{{ $slotTerisi }}</h2>
                </div>
            </div>
        </div>

        {{-- SLOT KOSONG --}}
        <div class="col-lg-4 col-12 mb-3">
            <div class="card shadow border-0" style="border-left:6px solid #28a745;">
                <div class="card-body py-4">
                    <h6 class="text-muted">Slot Kosong</h6>
                    <h2 class="fw-bold">{{ $slotKosong }}</h2>
                </div>
            </div>
        </div>

        {{-- TOTAL KEUNTUNGAN --}}
        <div class="col-lg-4 col-12 mb-3">
            <div class="card shadow border-0" style="border-left:6px solid #0d6efd;">
                <div class="card-body py-4">
                    <h6 class="text-muted">Total Keuntungan</h6>
                    <h2 class="fw-bold">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        {{-- Grafik Line --}}
        <div class="col-lg-8 mb-3">
            <div class="card shadow-sm border-0" style="border-radius:12px;">
                <div class="card-body">
                    <h5 class="card-title">Keuntungan Per Bulan</h5>
                    <canvas id="earningChart" height="140"></canvas>
                </div>
            </div>
        </div>

        {{-- Last 3 Payments --}}
        <div class="col-lg-4 mb-3">
            <div class="card shadow-sm border-0" style="border-radius:12px;">
                <div class="card-body">
                    <h5 class="card-title mb-3">Last 3 Payments</h5>

                    @forelse($lastPayments as $payment)
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded"
                             style="background:#f8f9fa; border:1px solid #e5e5e5;">

                            <div>
                                <div class="fw-bold">{{ $payment->ticket_code }}</div>
                                <div class="text-muted" style="font-size: 13px;">
                                    {{ $payment->exit_time }}
                                </div>
                            </div>

                            <div class="text-end">
                                <span class="fw-bold text-primary">
                                    Rp {{ number_format($payment->tarif->rate, 0, ',', '.') }}
                                </span>
                            </div>

                        </div>
                    @empty
                        <p class="text-muted">Belum ada pembayaran.</p>
                    @endforelse

                </div>
            </div>
        </div>

    </div>

</section>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('earningChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthLabels) !!},
        datasets: [{
            label: "Keuntungan (Rp)",
            data: {!! json_encode($monthlyEarnings) !!},
            tension: 0.45,
            fill: true,
            pointRadius: 5,
            pointBackgroundColor: "#0d6efd",
            backgroundColor: "rgba(13, 110, 253, 0.15)",
            borderColor: "#0d6efd",
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 1000000,
                ticks: {
                    callback: value => "Rp " + value.toLocaleString()
                }
            }
        }
    }
});
</script>

@endsection
