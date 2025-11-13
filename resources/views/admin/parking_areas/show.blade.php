@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Detail Area Parkir</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.parking-areas.index') }}">Area Parkir</a></li>
            <li class="breadcrumb-item active">{{ $area->name }}</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body mt-3">
            {{-- Informasi Area --}}
            <h5 class="card-title">Informasi Area Parkir</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nama Area:</strong> {{ $area->name }}</p>
                    <p><strong>Lokasi:</strong> {{ $area->location ?? '-' }}</p>
                    <p><strong>Tipe Kendaraan:</strong> {{ $area->vehicleType->name ?? '-' }}</p>
                    <p><strong>Total Slot:</strong> {{ $totalSlots }}</p>
                    <p><strong>Total Sensor:</strong> {{ $area->slots->pluck('sensor')->filter()->count() }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tersedia:</strong> {{ $emptySlots }}</p>
                    <p><strong>Terisi:</strong> {{ $usedSlots }}</p>
                    <p><strong>Persentase Terisi:</strong> {{ $percentageUsed }}%</p>

                    {{-- Progress Bar --}}
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar 
                            @if ($percentageUsed < 50) bg-success
                            @elseif ($percentageUsed < 80) bg-warning
                            @else bg-danger @endif"
                            role="progressbar"
                            style="width: {{ $percentageUsed }}%;"
                            aria-valuenow="{{ $percentageUsed }}"
                            aria-valuemin="0"
                            aria-valuemax="100">
                            {{ $percentageUsed }}%
                        </div>
                    </div>

                    {{-- Status Area --}}
                    <p>
                        <strong>Status Area:</strong>
                        @if ($percentageUsed < 50)
                            <span class="badge bg-success">Longgar</span>
                        @elseif ($percentageUsed < 80)
                            <span class="badge bg-warning text-dark">Hampir Penuh</span>
                        @else
                            <span class="badge bg-danger">Penuh</span>
                        @endif
                    </p>
                </div>
            </div>

            <hr>

            {{-- Daftar Slot + Sensor --}}
            <h5 class="card-title">Daftar Slot Parkir & Sensor</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nomor Slot</th>
                            <th>Status Slot</th>
                            <th>Kode Sensor</th>
                            <th>Tipe Sensor</th>
                            <th>Status Sensor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($area->slots as $index => $slot)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $slot->slot_code }}</td>
                                <td>
                                    @if ($slot->status == 'empty')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif ($slot->status == 'occupied')
                                        <span class="badge bg-danger">Terisi</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Maintenance</span>
                                    @endif
                                </td>
                                <td>{{ $slot->sensor->code ?? '-' }}</td>
                                <td>{{ $slot->sensor->type ?? '-' }}</td>
                                <td>
                                    @if($slot->sensor)
                                        @if($slot->sensor->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada slot parkir</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.parking-areas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('admin.parking-areas.edit', $area->id) }}" class="btn btn-primary ms-2">
                    <i class="bi bi-pencil"></i> Edit Area
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
