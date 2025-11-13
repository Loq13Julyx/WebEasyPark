@extends('layouts.app')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <div>
            <h1>Detail Petugas</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.petugas.index') }}">Petugas</a></li>
                    <li class="breadcrumb-item active">Detail Petugas</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.petugas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <section class="section mt-3">
        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="row g-0">
                {{-- SIDEBAR PROFIL --}}
                <div class="col-md-4 bg-light border-end text-center p-4">
                    @php
                        $petugas = $user->petugas;
                        $photo = $petugas->photo ?? null;
                        $gender = $petugas->gender ?? null;

                        if ($photo) {
                            $photoPath = asset('storage/' . $photo);
                        } elseif ($gender === 'L') {
                            $photoPath = asset('images/default-male.png');
                        } elseif ($gender === 'P') {
                            $photoPath = asset('images/default-female.png');
                        } else {
                            $photoPath = asset('images/default-user.png');
                        }
                    @endphp

                    <div class="position-relative mx-auto mb-3" style="width: 140px; height: 140px;">
                        <img src="{{ $photoPath }}" class="rounded-circle shadow-sm w-100 h-100"
                            style="object-fit: cover;" alt="Foto {{ $user->name }}">
                    </div>

                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge bg-primary px-3 py-2 text-uppercase">{{ $user->role }}</span>

                    <hr class="my-4">

                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.petugas.edit', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <form action="{{ route('admin.petugas.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus data petugas ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- DETAIL PETUGAS --}}
                <div class="col-md-8 p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person-lines-fill text-primary me-2"></i>Informasi Petugas</h5>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <tbody>
                                <tr>
                                    <th class="text-muted" style="width: 35%;">Nama Lengkap</th>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Email</th>
                                    <td class="fw-semibold">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">NIP</th>
                                    <td class="fw-semibold">{{ $petugas->nip ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nomor Telepon</th>
                                    <td class="fw-semibold">{{ $petugas->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Jenis Kelamin</th>
                                    <td class="fw-semibold">
                                        {{ $petugas->gender === 'L' ? 'Laki-laki' : ($petugas->gender === 'P' ? 'Perempuan' : '-') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Shift</th>
                                    <td class="fw-semibold text-capitalize">{{ $petugas->shift ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Alamat</th>
                                    <td class="fw-semibold">{{ $petugas->address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Status</th>
                                    <td>
                                        <span
                                            class="badge px-3 py-2 {{ $petugas->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($petugas->status ?? '-') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tanggal Dibuat</th>
                                    <td class="fw-semibold">{{ $user->created_at->translatedFormat('d F Y, H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
