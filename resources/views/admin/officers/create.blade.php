@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Tambah Petugas</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.officers.index') }}">Petugas</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mt-3">Form Tambah Petugas</h5>

            <form action="{{ route('admin.officers.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nama Petugas <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Masukkan nama lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="contoh@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label for="password" class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimal 6 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.officers.index') }}" class="btn btn-secondary me-2">
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
