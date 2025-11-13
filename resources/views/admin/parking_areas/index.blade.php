@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Manajemen Area Parkir</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Area Parkir</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
                <h5 class="card-title mb-0">Daftar Area Parkir</h5>
                <a href="{{ route('admin.parking-areas.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Area Parkir
                </a>
            </div>

            {{-- Tabel daftar area parkir --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Area</th>
                            <th>Lokasi</th>
                            <th>Tipe Kendaraan</th>
                            <th>Total Slot</th>
                            <th>Terisi</th>
                            <th>Kosong</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($areas as $index => $area)
                            @php
                                $totalSlots = $area->slots->count();
                                $occupiedSlots = $area->slots->where('status', 'occupied')->count();
                                $emptySlots = $area->slots->where('status', 'empty')->count();
                            @endphp
                            <tr>
                                <td>{{ $areas->firstItem() + $index }}</td>
                                <td class="fw-semibold">{{ $area->name }}</td>
                                <td>{{ $area->location ?? '-' }}</td>
                                <td>{{ $area->vehicleType->name ?? '-' }}</td>
                                <td>{{ $totalSlots }}</td>
                                <td>{{ $occupiedSlots }}</td>
                                <td>{{ $emptySlots }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.parking-areas.show', $area->id) }}"
                                        class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.parking-areas.edit', $area->id) }}"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Tombol hapus --}}
                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $area->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data area parkir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $areas->withQueryString()->links() }}
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notifikasi dari session
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            confirmButtonText: 'OK'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: @json(session('error')),
            confirmButtonText: 'OK'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: @json($errors->first()),
            confirmButtonText: 'OK'
        });
    @endif

    // Konfirmasi hapus area parkir
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            let areaId = this.dataset.id;

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data area parkir akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // buat form dinamis untuk DELETE
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/parking-areas/${areaId}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        });
    });
</script>
@endsection
