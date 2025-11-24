@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Manajemen Gate (Pintu Masuk/Keluar)</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Gate</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
                    <h5 class="card-title mb-0">Daftar Gate</h5>
                    <a href="{{ route('admin.gates.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Gate
                    </a>
                </div>

                {{-- Tabel daftar gate --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Gate</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gates as $index => $gate)
                                <tr>
                                    <td>{{ $gates->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $gate->name }}</td>
                                    <td>{{ $gate->location ?? '-' }}</td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if ($gate->status == 'open')
                                            <span class="badge bg-success">Terbuka</span>
                                        @else
                                            <span class="badge bg-danger">Tertutup</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.gates.edit', $gate->id) }}" class="btn btn-sm btn-warning"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                            data-id="{{ $gate->id }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data gate.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $gates->withQueryString()->links() }}
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

        // Konfirmasi hapus gate
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                let gateId = this.dataset.id;

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data gate akan dihapus!",
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
                        form.action = `/admin/gates/${gateId}`;
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
