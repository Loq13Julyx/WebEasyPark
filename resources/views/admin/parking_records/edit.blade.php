@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Edit Data Parkir</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.parking-records.index') }}">Data Parkir</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="card-title mb-3">Form Edit Data Parkir</h5>

                <form action="{{ route('admin.parking-records.update', $record->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Kode Tiket --}}
                        <div class="col-md-6">
                            <label class="form-label">Kode Tiket</label>
                            <input type="text" class="form-control" value="{{ $record->ticket_code }}" disabled>
                        </div>

                        {{-- Waktu Masuk --}}
                        <div class="col-md-6">
                            <label class="form-label">Waktu Masuk</label>
                            <input type="text" class="form-control"
                                value="{{ \Carbon\Carbon::parse($record->entry_time)->format('d/m/Y H:i') }}" disabled>
                        </div>

                        {{-- Status Pembayaran --}}
                        <div class="col-md-6">
                            <label for="payment_status" class="form-label">Status Pembayaran</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="unpaid" {{ $record->payment_status == 'unpaid' ? 'selected' : '' }}>
                                    Menunggu Pembayaran
                                </option>
                                <option value="paid" {{ $record->payment_status == 'paid' ? 'selected' : '' }}>
                                    Pembayaran Selesai
                                </option>
                            </select>
                        </div>

                        {{-- Status Parkir (Readonly, otomatis) --}}
                        <div class="col-md-6">
                            <label class="form-label">Status Parkir</label>
                            <input type="text" class="form-control"
                                value="{{ $record->payment_status == 'unpaid' ? 'Masih Parkir' : 'Telah Keluar' }}"
                                disabled>
                        </div>

                        {{-- Waktu Keluar --}}
                        <div class="col-md-6" id="exit_time_container">
                            <label for="exit_time" class="form-label">Waktu Keluar</label>
                            <input type="datetime-local" name="exit_time" id="exit_time" class="form-control"
                                value="{{ $record->exit_time ? \Carbon\Carbon::parse($record->exit_time)->format('Y-m-d\TH:i') : '' }}"
                                @if ($record->payment_status == 'unpaid') disabled @endif>
                            <small class="text-muted">Otomatis aktif jika pembayaran selesai.</small>
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.parking-records.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>

                </form>

            </div>
        </div>
    </section>

    {{-- Script untuk otomatis mengaktifkan exit_time jika paid --}}
    <script>
        const payment = document.getElementById('payment_status');
        const exitTime = document.getElementById('exit_time');

        payment.addEventListener('change', () => {
            if (payment.value === 'paid') {
                exitTime.disabled = false;
            } else {
                exitTime.disabled = true;
                exitTime.value = '';
            }
        });
    </script>
@endsection
