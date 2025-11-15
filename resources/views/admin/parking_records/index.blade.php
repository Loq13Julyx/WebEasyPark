@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Manajemen Data Parkir</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Data Parkir</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-2 mt-3 flex-wrap gap-2">
                    <h5 class="card-title mb-0">Daftar Data Parkir</h5>
                </div>

                {{-- Filter --}}
                <form method="GET" class="mb-4">
                    <div class="row g-2 align-items-end">

                        {{-- Tanggal Mulai --}}
                        <div class="col-auto">
                            <label class="form-label fw-semibold small">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="form-control form-control-sm" style="width: 180px;">
                        </div>

                        {{-- Tanggal Akhir --}}
                        <div class="col-auto">
                            <label class="form-label fw-semibold small">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="form-control form-control-sm" style="width: 180px;">
                        </div>

                        {{-- Status Parkir --}}
                        <div class="col-auto">
                            <label class="form-label fw-semibold small">Status Parkir</label>
                            <select name="status" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Semua</option>
                                <option value="in" {{ request('status') == 'in' ? 'selected' : '' }}>Sedang Parkir
                                </option>
                                <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Telah Keluar
                                </option>
                            </select>
                        </div>

                        {{-- Status Pembayaran --}}
                        <div class="col-auto">
                            <label class="form-label fw-semibold small">Status Pembayaran</label>
                            <select name="payment_status" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Semua</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                    Pembayaran Selesai</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                    Menunggu Pembayaran</option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-auto">
                            <button class="btn btn-sm btn-primary">
                                <i class="bi bi-filter"></i>
                            </button>
                        </div>

                        <div class="col-auto">
                            <a href="{{ route('admin.parking-records.index') }}" class="btn btn-sm btn-secondary">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kode Tiket</th>
                                <th>Tarif</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status Parkir</th>
                                <th>Status Pembayaran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $index => $record)
                                <tr>
                                    <td>{{ $records->firstItem() + $index }}</td>

                                    <td class="fw-semibold">{{ $record->ticket_code }}</td>

                                    <td>
                                        Rp {{ number_format($record->tarif->rate ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td>{{ $record->entry_time }}</td>
                                    <td>{{ $record->exit_time ?? '-' }}</td>

                                    {{-- Status Parkir --}}
                                    <td>
                                        @if ($record->status == 'in')
                                            <span class="badge bg-warning text-dark">Sedang Parkir</span>
                                        @else
                                            <span class="badge bg-success">Telah Keluar</span>
                                        @endif
                                    </td>

                                    {{-- Status Pembayaran --}}
                                    <td>
                                        @if ($record->payment_status == 'paid')
                                            <span class="badge bg-primary">Pembayaran Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Menunggu Pembayaran</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-center">

                                        <a href="{{ route('admin.parking-records.show', $record->id) }}"
                                            class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                            data-id="{{ $record->id }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        Tidak ada data parkir ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $records->withQueryString()->links() }}
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Konfirmasi hapus
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.dataset.id;

                Swal.fire({
                    title: 'Hapus data?',
                    text: "Data parkir akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/parking-records/${id}`;
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
