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
                <a href="{{ route('admin.officers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Petugas
                </a>
            </div>

            {{-- Tabel daftar petugas --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($officers as $index => $officer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $officer->name }}</td>
                                <td>{{ $officer->email }}</td>

                                <td class="text-center">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.officers.edit', $officer->id) }}"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $officer->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data petugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notifikasi
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
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

    // Konfirmasi hapus petugas
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            let officerId = this.dataset.id;

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data petugas akan dihapus!",
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
                    form.action = `/admin/officers/${officerId}`;
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
