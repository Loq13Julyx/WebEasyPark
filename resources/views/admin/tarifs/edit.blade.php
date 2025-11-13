@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Tarif Parkir</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.tarifs.index') }}">Tarif Parkir</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mt-3">Form Edit Tarif</h5>

            <form action="{{ route('admin.tarifs.update', $tarif->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Jenis Kendaraan --}}
                    <div class="col-md-6">
                        <label for="vehicle_type_id" class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                        <select name="vehicle_type_id" id="vehicle_type_id" class="form-select @error('vehicle_type_id') is-invalid @enderror">
                            <option value="">-- Pilih Jenis Kendaraan --</option>
                            @foreach ($vehicleTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('vehicle_type_id', $tarif->vehicle_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tarif Awal --}}
                    <div class="col-md-6">
                        <label for="rate" class="form-label">Tarif Awal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="rate" name="rate"
                            value="{{ old('rate', $tarif->rate) }}"
                            class="form-control @error('rate') is-invalid @enderror"
                            placeholder="Masukkan tarif awal">
                        @error('rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.tarifs.index') }}" class="btn btn-secondary me-2">
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
