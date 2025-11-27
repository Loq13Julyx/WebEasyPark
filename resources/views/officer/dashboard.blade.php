@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard Officer</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('officer.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">

        {{-- ROW 1: STATISTIK --}}
        <div class="row">

            {{-- SLOT KOSONG --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #28a745;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Slot Kosong</h6>
                                <h3 class="fw-bold mb-0 text-success">{{ $slotKosong }}/{{ $slotTotal }}</h3>
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

            {{-- SLOT TERISI --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #dc3545;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Slot Terisi</h6>
                                <h3 class="fw-bold mb-0 text-danger">{{ $slotTerisi }}</h3>
                                <small class="text-muted">
                                    <i class="bi bi-car-front"></i> saat ini
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-car-front-fill" style="font-size: 2rem; color: #dc3545;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KENDARAAN MASUK --}}
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

            {{-- KENDARAAN KELUAR --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #fd7e14;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Kendaraan Keluar</h6>
                                <h3 class="fw-bold mb-0 text-warning">{{ $kendaraanKeluarHariIni }}</h3>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-day"></i> Hari ini
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-arrow-up-circle-fill" style="font-size: 2rem; color: #fd7e14;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ROW 2: DAFTAR PARKIR RECORD HARI INI --}}
        <div class="row mt-2">
            <div class="col-12 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-list-ul text-primary"></i>
                                Daftar Parkir Hari Ini
                            </h5>
                            {{-- TOTAL SELURUH DATA --}}
                            <span class="badge bg-primary">{{ $recordsHariIni->total() }} Record</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Tiket</th>
                                        <th>Jenis Kendaraan</th>
                                        <th>Slot Parkir</th>
                                        <th>Area</th>
                                        <th>Waktu Masuk</th>
                                        <th>Waktu Keluar</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recordsHariIni as $index => $record)
                                        <tr>
                                            {{-- NOMOR URUT TIDAK RESET --}}
                                            <td>{{ $recordsHariIni->firstItem() + $index }}</td>

                                            <td>
                                                <span class="badge bg-dark">{{ $record->ticket_code }}</span>
                                            </td>

                                            <td>
                                                @if ($record->tarif && $record->tarif->vehicleType)
                                                    <i
                                                        class="bi bi-{{ $record->tarif->vehicleType->name == 'Motor' ? 'bicycle' : 'car-front' }}"></i>
                                                    {{ $record->tarif->vehicleType->name }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($record->parkingSlot)
                                                    <span class="badge bg-secondary">
                                                        {{ $record->parkingSlot->slot_code }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($record->parkingSlot && $record->parkingSlot->area)
                                                    {{ $record->parkingSlot->area->name }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($record->entry_time)
                                                    <small>{{ \Carbon\Carbon::parse($record->entry_time)->format('H:i') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($record->exit_time)
                                                    <small>{{ \Carbon\Carbon::parse($record->exit_time)->format('H:i') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($record->status == 'in')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-arrow-down-circle"></i> Masuk
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-arrow-up-circle"></i> Keluar
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($record->payment_status == 'paid')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Lunas
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock"></i> Belum Bayar
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                                <span class="text-muted">Tidak ada data parkir hari ini</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION --}}
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $recordsHariIni->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
