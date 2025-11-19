@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Manajemen Slot Parkir</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Slot Parkir</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
                    <h5 class="card-title mb-0">Daftar Slot Parkir</h5>
                    <a href="{{ route('admin.parking-slots.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Slot Parkir
                    </a>
                </div>

                {{-- Tabel daftar slot parkir --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Area Parkir</th>
                                <th>Kode Slot</th>
                                <th>Jarak dari Pintu Masuk (m)</th>
                                <th>Arah Rute</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($slots as $index => $slot)
                                <tr>
                                    <td>{{ $slots->firstItem() + $index }}</td>
                                    <td>{{ $slot->area->name ?? '-' }}</td>
                                    <td class="fw-semibold">{{ $slot->slot_code }}</td>
                                    <td>{{ $slot->distance_from_entry ? $slot->distance_from_entry . ' m' : '-' }}</td>

                                    {{-- ARAH RUTE --}}
                                    <td style="max-width: 250px;">
                                        {{ $slot->route_direction ?? '-' }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if ($slot->status == 'empty')
                                            <span class="badge bg-success">Kosong</span>
                                        @else
                                            <span class="badge bg-danger">Terisi</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.parking-slots.edit', $slot->id) }}"
                                            class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                            data-id="{{ $slot->id }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data slot parkir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $slots->withQueryString()->links() }}
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

        // Konfirmasi hapus slot parkir
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                let slotId = this.dataset.id;

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data slot parkir akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/parking-slots/${slotId}`;
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
