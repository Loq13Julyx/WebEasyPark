@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Tipe Kendaraan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.vehicle-types.index') }}">Tipe Kendaraan</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mt-3">Form Edit Tipe Kendaraan</h5>

            <form action="{{ route('admin.vehicle-types.update', $vehicleType->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Nama Tipe Kendaraan --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nama Tipe Kendaraan <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $vehicleType->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Contoh: Motor, Mobil">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.vehicle-types.index') }}" class="btn btn-secondary me-2">
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
