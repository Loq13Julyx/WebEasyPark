@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Area Parkir</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.parking-areas.index') }}">Area Parkir</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mt-3">Form Edit Area Parkir</h5>

            <form action="{{ route('admin.parking-areas.update', $area->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Nama Area --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nama Area Parkir <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $area->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Contoh: Area A, Basement 1, Lantai 2">
                        <small class="text-muted">Ubah nama area parkir bila diperlukan.</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lokasi --}}
                    <div class="col-md-6">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" id="location" name="location"
                            value="{{ old('location', $area->location) }}"
                            class="form-control @error('location') is-invalid @enderror"
                            placeholder="Contoh: Sisi Timur, Basement, Dekat Gerbang Barat">
                        <small class="text-muted">Perbarui lokasi area parkir jika terjadi perubahan posisi fisik.</small>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tipe Kendaraan --}}
                    <div class="col-md-6">
                        <label for="vehicle_type_id" class="form-label">
                            Tipe Kendaraan <span class="text-danger">*</span>
                        </label>
                        <select id="vehicle_type_id" name="vehicle_type_id"
                            class="form-select @error('vehicle_type_id') is-invalid @enderror">
                            <option value="">-- Pilih Tipe Kendaraan --</option>
                            @foreach($vehicleTypes as $type)
                                <option value="{{ $type->id }}" 
                                    {{ old('vehicle_type_id', $area->vehicle_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih tipe kendaraan yang bisa parkir di area ini.</small>
                        @error('vehicle_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.parking-areas.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
