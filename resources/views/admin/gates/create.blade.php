@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Gate</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.gates.index') }}">Gate</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mt-3">Form Tambah Gate</h5>

                <form action="{{ route('admin.gates.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        {{-- Nama Gate --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                Nama Gate <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Contoh: Gate Utama, Gate Timur">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Lokasi Gate --}}
                        <div class="col-md-6">
                            <label for="location" class="form-label">
                                Lokasi Gate <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                class="form-control @error('location') is-invalid @enderror"
                                placeholder="Contoh: Sebelah Gedung A, Depan Gerbang Barat">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Gate --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">-- Pilih Status --</option>
                                <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Terbuka</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Tertutup</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.gates.index') }}" class="btn btn-secondary me-2">
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
