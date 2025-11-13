@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Edit Petugas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.petugas.index') }}">Petugas</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title">Form Edit Petugas</h5>

                <form action="{{ route('admin.petugas.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- ================== DATA AKUN ================== --}}
                    <h6 class="fw-bold mb-3">Data Akun</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" placeholder="Nama lengkap petugas">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" placeholder="Email aktif petugas">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- ================== DATA DETAIL PETUGAS ================== --}}
                    <h6 class="fw-bold mt-4 mb-3">Data Petugas</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" maxlength="19"
                                class="form-control @error('nip') is-invalid @enderror"
                                value="{{ old('nip', $user->petugas->nip ?? '') }}" placeholder="P123456789012345678">
                            @error('nip')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $user->petugas->phone ?? '') }}" placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('gender', $user->petugas->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $user->petugas->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Shift</label>
                            <select name="shift" class="form-select @error('shift') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="pagi" {{ old('shift', $user->petugas->shift ?? '') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                <option value="siang" {{ old('shift', $user->petugas->shift ?? '') == 'siang' ? 'selected' : '' }}>Siang</option>
                                <option value="malam" {{ old('shift', $user->petugas->shift ?? '') == 'malam' ? 'selected' : '' }}>Malam</option>
                            </select>
                            @error('shift')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="aktif" {{ old('status', $user->petugas->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $user->petugas->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Foto</label>
                            <input type="file" name="photo" id="photoInput" accept="image/*"
                                class="form-control @error('photo') is-invalid @enderror">
                            @error('photo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <div class="mt-2">
                                @if (!empty($user->petugas->photo))
                                    <img id="photoPreview" src="{{ asset('storage/' . $user->petugas->photo) }}"
                                        alt="Foto Petugas" class="img-thumbnail" style="max-width: 150px;">
                                @else
                                    <img id="photoPreview" src="#" alt="Preview Foto" class="img-thumbnail"
                                        style="display:none; max-width: 150px;">
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror"
                                placeholder="Alamat lengkap petugas">{{ old('address', $user->petugas->address ?? '') }}</textarea>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Script Preview Foto --}}
    <script>
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');

        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    photoPreview.src = event.target.result;
                    photoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.style.display = 'none';
            }
        });
    </script>
@endsection
