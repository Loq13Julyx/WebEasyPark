@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Detail Data Parkir</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.parking-records.index') }}">Data Parkir</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="card-title mb-3">Informasi Parkir</h5>

                <div class="row g-3">

                    {{-- Kode Tiket --}}
                    <div class="col-md-6">
                        <label class="form-label">Kode Tiket</label>
                        <input type="text" class="form-control" value="{{ $record->ticket_code }}" disabled>
                    </div>

                    {{-- Tarif --}}
                    <div class="col-md-6">
                        <label class="form-label">Tarif</label>
                        <input type="text" class="form-control"
                            value="Rp {{ number_format($record->tarif->rate ?? 0, 0, ',', '.') }}" disabled>
                    </div>

                    {{-- Waktu Masuk --}}
                    <div class="col-md-6">
                        <label class="form-label">Waktu Masuk</label>
                        <input type="text" class="form-control"
                            value="{{ \Carbon\Carbon::parse($record->entry_time)->format('d/m/Y H:i') }}" disabled>
                    </div>

                    {{-- Waktu Keluar --}}
                    <div class="col-md-6">
                        <label class="form-label">Waktu Keluar</label>
                        <input type="text" class="form-control"
                            value="{{ $record->exit_time ? \Carbon\Carbon::parse($record->exit_time)->format('d/m/Y H:i') : '-' }}"
                            disabled>
                    </div>

                    {{-- Durasi --}}
                    @php
                        $entry = \Carbon\Carbon::parse($record->entry_time);
                        $exit = $record->exit_time ? \Carbon\Carbon::parse($record->exit_time) : now();
                        $duration = $entry->diff($exit);
                    @endphp

                    <div class="col-md-6">
                        <label class="form-label">Durasi Parkir</label>
                        <input type="text" class="form-control"
                            value="{{ $duration->days * 24 + $duration->h }} jam {{ $duration->i }} mnt" disabled>
                    </div>

                    {{-- Status Pembayaran --}}
                    <div class="col-md-6">
                        <label class="form-label">Status Pembayaran</label>
                        <input type="text" class="form-control"
                            value="{{ $record->payment_status === 'paid' ? 'Pembayaran Selesai' : 'Menunggu Pembayaran' }}"
                            disabled>
                    </div>

                    {{-- Status Parkir --}}
                    <div class="col-md-6">
                        <label class="form-label">Status Parkir</label>
                        <input type="text" class="form-control"
                            value="{{ $record->status === 'in' ? 'Masih Parkir' : 'Telah Keluar' }}" disabled>
                    </div>

                </div>

                {{-- Tombol --}}
                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('admin.parking-records.edit', $record->id) }}" class="btn btn-warning text-dark">
                        Edit
                    </a>

                    <form action="{{ route('admin.parking-records.destroy', $record->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Hapus
                        </button>
                    </form>

                    <a href="{{ route('admin.parking-records.index') }}" class="btn btn-secondary">Kembali</a>
                </div>

            </div>
        </div>
    </section>
@endsection
