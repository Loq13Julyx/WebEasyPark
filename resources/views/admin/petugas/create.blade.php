@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Petugas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.petugas.index') }}">Petugas</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title">Form Tambah Petugas</h5>

                <form action="{{ route('admin.petugas.store') }}" method="POST" enctype="multipart/form-data" id="petugasForm">
                    @csrf

                    {{-- ================== STEP 1: DATA AKUN ================== --}}
                    <div id="step1">
                        <h6 class="fw-bold mb-3">Tahap 1: Data Akun</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama</label>
                                <small class="text-muted d-block">Masukkan nama lengkap sesuai identitas petugas.</small>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Contoh: Alief Chandra">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <small class="text-muted d-block">Gunakan email aktif petugas untuk login.</small>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="contoh@email.com">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <small class="text-muted d-block">Minimal 8 karakter, gabungkan huruf & angka untuk keamanan.</small>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <small class="text-muted d-block">Masukkan kembali password yang sama untuk verifikasi.</small>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirm">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" id="nextStep" class="btn btn-primary">Selanjutnya</button>
                        </div>
                    </div>

                    {{-- ================== STEP 2: DATA DETAIL PETUGAS ================== --}}
                    <div id="step2" style="display:none;">
                        <h6 class="fw-bold mb-3">Tahap 2: Data Petugas</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIP</label>
                                <small class="text-muted d-block">Gunakan format <b>P</b> di depan diikuti 18 angka. Contoh: P123456789012345678</small>
                                <input type="text" name="nip" maxlength="19"
                                    class="form-control @error('nip') is-invalid @enderror"
                                    value="{{ old('nip') }}" placeholder="P123456789012345678">
                                @error('nip')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <small class="text-muted d-block">Gunakan nomor HP aktif (diawali 08).</small>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <small class="text-muted d-block">Pilih jenis kelamin sesuai data diri.</small>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shift</label>
                                <small class="text-muted d-block">Tentukan jadwal kerja petugas.</small>
                                <select name="shift" class="form-select @error('shift') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="pagi" {{ old('shift') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                    <option value="siang" {{ old('shift') == 'siang' ? 'selected' : '' }}>Siang</option>
                                    <option value="malam" {{ old('shift') == 'malam' ? 'selected' : '' }}>Malam</option>
                                </select>
                                @error('shift')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <small class="text-muted d-block">Tentukan apakah petugas masih aktif bekerja atau tidak.</small>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto</label>
                                <small class="text-muted d-block">Unggah foto terbaru petugas (format JPG/PNG, max 2MB).</small>
                                <input type="file" name="photo" id="photoInput" accept="image/*"
                                    class="form-control @error('photo') is-invalid @enderror">
                                @error('photo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <div class="mt-2">
                                    <img id="photoPreview" src="#" alt="Preview Foto" class="img-thumbnail"
                                        style="display:none; max-width: 150px;">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <small class="text-muted d-block">Masukkan alamat tempat tinggal lengkap petugas.</small>
                                <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror"
                                    placeholder="Contoh: Jl. Mastrip No. 22, Jember">{{ old('address') }}</textarea>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" id="prevStep" class="btn btn-secondary">Kembali</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Script navigasi & interaksi --}}
    <script>
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        document.getElementById('nextStep').addEventListener('click', () => {
            step1.style.display = 'none';
            step2.style.display = 'block';
        });
        document.getElementById('prevStep').addEventListener('click', () => {
            step2.style.display = 'none';
            step1.style.display = 'block';
        });

        // Toggle Password
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        togglePassword.addEventListener('click', () => {
            const icon = togglePassword.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });

        // Toggle Konfirmasi Password
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        togglePasswordConfirm.addEventListener('click', () => {
            const icon = togglePasswordConfirm.querySelector('i');
            if (passwordConfirmInput.type === 'password') {
                passwordConfirmInput.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordConfirmInput.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });

        // Preview Foto
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
