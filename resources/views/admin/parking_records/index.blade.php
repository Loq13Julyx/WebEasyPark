@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Manajemen Data Parkir</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Data Parkir</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
                <h5 class="card-title mb-0">Daftar Data Parkir Lengkap</h5>
                <a href="{{ route('admin.parking-records.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
            </div>

            {{-- Tabel daftar data parkir --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Tiket</th>
                            <th>Slot</th>
                            <th>Tipe Kendaraan</th>
                            <th>Tarif Awal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Durasi</th>
                            <th>Status Pembayaran</th>
                            <th>Status Parkir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $index => $record)
                            @php
                                $entry = \Carbon\Carbon::parse($record->entry_time);
                                $exit = $record->exit_time ? \Carbon\Carbon::parse($record->exit_time) : now();
                                $duration = $entry->diff($exit);
                            @endphp
                            <tr>
                                <td>{{ $records->firstItem() + $index }}</td>
                                <td class="fw-semibold">{{ $record->ticket_code }}</td>
                                <td>{{ $record->parkingSlot->slot_code ?? '-' }}</td>
                                <td>{{ $record->vehicleType->name ?? '-' }}</td>
                                <td>Rp {{ number_format($record->tarif->initial_rate ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $entry->format('d/m/Y H:i') }}</td>
                                <td>{{ $record->exit_time ? $exit->format('d/m/Y H:i') : '-' }}</td>
                                <td>{{ $duration->days * 24 + $duration->h }} jam {{ $duration->i }} mnt</td>
                                <td>
                                    <span class="badge {{ $record->payment_status === 'paid' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                        {{ $record->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $record->status === 'in' ? 'bg-primary text-white' : 'bg-secondary text-white' }}">
                                        {{ strtoupper($record->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">Belum ada data parkir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $records->withQueryString()->links() }}
            </div>

        </div>
    </div>
</section>
@endsection
