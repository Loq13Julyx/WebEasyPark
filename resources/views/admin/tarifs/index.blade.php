@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Manajemen Tarif Parkir</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Tarif Parkir</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
                <h5 class="card-title mb-0">Daftar Tarif Parkir</h5>
                <a href="{{ route('admin.tarifs.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Tarif
                </a>
            </div>

            {{-- Tabel daftar tarif --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Jenis Kendaraan</th>
                            <th>Tarif (Rp)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tarifs as $index => $tarif)
                            <tr>
                                <td>{{ $tarifs->firstItem() + $index }}</td>
                                <td>{{ $tarif->vehicleType->name ?? '-' }}</td>
                                <td>Rp {{ number_format($tarif->rate, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.tarifs.edit', $tarif->id) }}" 
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Tombol hapus dengan SweetAlert --}}
                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $tarif->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data tarif parkir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $tarifs->withQueryString()->links() }}
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

    // Konfirmasi hapus tarif
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            let tarifId = this.dataset.id;

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data tarif akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis untuk DELETE
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/tarifs/${tarifId}`;
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
