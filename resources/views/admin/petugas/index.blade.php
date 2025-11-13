@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Manajemen Petugas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Petugas</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
                    <h5 class="card-title mb-0">Daftar Petugas</h5>
                    <a href="{{ route('admin.petugas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Petugas
                    </a>
                </div>

                {{-- Filter & Pencarian --}}
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama / email / NIP"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="shift" class="form-select">
                            <option value="">Semua Shift</option>
                            <option value="pagi" {{ request('shift') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                            <option value="siang" {{ request('shift') == 'siang' ? 'selected' : '' }}>Siang</option>
                            <option value="malam" {{ request('shift') == 'malam' ? 'selected' : '' }}>Malam</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </form>

                {{-- Tabel daftar petugas --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Petugas</th>
                                <th>NIP</th>
                                <th>Shift</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
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
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>

                                    {{-- Petugas (foto + nama + email) --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center border"
                                                    style="width: 45px; height: 45px;">
                                                    <img src="{{ $photoPath }}" alt="Foto {{ $user->name }}"
                                                        class="w-100 h-100" style="object-fit: cover;">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- NIP --}}
                                    <td>{{ $petugas->nip ?? '-' }}</td>

                                    {{-- Shift --}}
                                    <td>
                                        <span class="badge bg-secondary text-capitalize">
                                            {{ $petugas->shift ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if ($petugas && $petugas->status === 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>

                                    {{-- Tanggal dibuat --}}
                                    <td>{{ $user->created_at->format('d M Y') }}</td>

                                    {{-- Aksi --}}
                                    <td class="text-center">
                                        <a href="{{ route('admin.petugas.show', $user->id) }}"
                                            class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.petugas.edit', $user->id) }}"
                                            class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.petugas.destroy', $user->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus petugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data petugas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
