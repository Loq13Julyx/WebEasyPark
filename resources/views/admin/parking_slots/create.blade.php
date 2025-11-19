@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Tambah Slot Parkir</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.parking-slots.index') }}">Slot Parkir</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mt-3">Form Tambah Slot Parkir</h5>

            <form action="{{ route('admin.parking-slots.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Area Parkir --}}
                    <div class="col-md-6">
                        <label for="area_id" class="form-label">
                            Area Parkir <span class="text-danger">*</span>
                        </label>
                        <select name="area_id" id="area_id"
                            class="form-select @error('area_id') is-invalid @enderror">
                            <option value="">-- Pilih Area --</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}"
                                    {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kode Slot --}}
                    <div class="col-md-6">
                        <label for="slot_code" class="form-label">
                            Kode Slot <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            id="slot_code"
                            name="slot_code"
                            value="{{ old('slot_code') }}"
                            class="form-control @error('slot_code') is-invalid @enderror"
                            placeholder="Contoh: A1, B3">
                        @error('slot_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jarak dari Pintu Masuk --}}
                    <div class="col-md-6">
                        <label for="distance_from_entry" class="form-label">
                            Jarak dari Pintu Masuk (meter)
                        </label>
                        <input type="number"
                            id="distance_from_entry"
                            name="distance_from_entry"
                            value="{{ old('distance_from_entry') }}"
                            class="form-control @error('distance_from_entry') is-invalid @enderror"
                            min="0" placeholder="Contoh: 42">
                        @error('distance_from_entry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Route Direction --}}
                    <div class="col-md-6">
                        <label for="route_direction" class="form-label">
                            Rute Arah Menuju Slot <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            id="route_direction"
                            name="route_direction"
                            value="{{ old('route_direction') }}"
                            class="form-control @error('route_direction') is-invalid @enderror"
                            placeholder="Contoh: Lurus, belok kiri, lalu lurus ke slot A1">
                        <small class="text-muted">
                            Rute dapat dibuat manual atau mengikuti format otomatis dari sistem.
                        </small>
                        @error('route_direction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="status"
                            class="form-select @error('status') is-invalid @enderror">
                            <option value="">-- Pilih Status --</option>
                            <option value="empty" {{ old('status') == 'empty' ? 'selected' : '' }}>Kosong</option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Terisi</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.parking-slots.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection
