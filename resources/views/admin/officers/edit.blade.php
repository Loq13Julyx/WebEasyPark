@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Petugas</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.officers.index') }}">Petugas</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mt-3">Form Edit Petugas</h5>

            <form action="{{ route('admin.officers.update', $officer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nama Petugas <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $officer->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nama lengkap petugas">
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
                            value="{{ old('email', $officer->email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="email@domain.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password (Opsional) --}}
                    <div class="col-md-6">
                        <label for="password" class="form-label">
                            Password Baru (Opsional)
                        </label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Biarkan kosong jika tidak ganti password">
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
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection
