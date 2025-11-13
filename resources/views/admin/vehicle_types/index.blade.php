@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Manajemen Tipe Kendaraan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Tipe Kendaraan</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
                <h5 class="card-title mb-0">Daftar Tipe Kendaraan</h5>
                <a href="{{ route('admin.vehicle-types.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Tipe Kendaraan
                </a>
            </div>

            {{-- Tabel daftar tipe kendaraan --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Tipe Kendaraan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicleTypes as $index => $type)
                            <tr>
                                <td>{{ $vehicleTypes->firstItem() + $index }}</td>
                                <td class="fw-semibold">{{ $type->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.vehicle-types.edit', $type->id) }}"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Tombol hapus dengan SweetAlert --}}
                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $type->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada data tipe kendaraan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $vehicleTypes->withQueryString()->links() }}
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

    // Konfirmasi hapus tipe kendaraan
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            let typeId = this.dataset.id;

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data tipe kendaraan akan dihapus!",
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
                    form.action = `/admin/vehicle-types/${typeId}`;
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
