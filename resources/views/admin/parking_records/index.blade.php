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
        <div class="card shadow-sm border-0">
            <div class="card-body px-4 py-3">

                {{-- Header --}}
                <div class="mb-3">
                    <h5 class="fw-semibold mb-1">Daftar Data Parkir Lengkap</h5>
                    <p class="text-muted mb-0">Kelola semua data parkir di sini.</p>
                </div>

                {{-- Filter sejajar horizontal + Tombol Cetak --}}
                <div class="d-flex align-items-center gap-2 mb-4 flex-nowrap">

                    {{-- Form Filter --}}
                    <form action="{{ route('admin.parking-records.index') }}" method="GET"
                        class="d-flex align-items-center gap-2 flex-nowrap">

                        {{-- Pencarian teks --}}
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border rounded-end"
                                placeholder="Cari kode tiket..." value="{{ request('search') }}">
                        </div>

                        {{-- Status Bayar --}}
                        <select name="payment_status" class="form-select form-select-sm border rounded shadow-sm"
                            style="width: 180px;">
                            <option value="">Semua Status Bayar</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Pembayaran
                                Selesai</option>
                            <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Menunggu
                            </option>
                        </select>

                        {{-- Status Parkir --}}
                        <select name="status" class="form-select form-select-sm border rounded shadow-sm"
                            style="width: 180px;">
                            <option value="">Semua Status Parkir</option>
                            <option value="in" {{ request('status') === 'in' ? 'selected' : '' }}>Masih Parkir</option>
                            <option value="out" {{ request('status') === 'out' ? 'selected' : '' }}>Keluar</option>
                        </select>

                        {{-- Tombol Filter --}}
                        <button type="submit" class="btn btn-sm btn-primary rounded shadow-sm px-3">Filter</button>

                        {{-- Tombol Reset --}}
                        <a href="{{ route('admin.parking-records.index') }}"
                            class="btn btn-sm btn-secondary rounded shadow-sm px-3">Reset</a>
                    </form>

                    {{-- Tombol Cetak Laporan --}}
                    <a href="{{ route('admin.parking-records.print', request()->query()) }}" target="_blank"
                        class="btn btn-sm btn-success rounded shadow-sm px-3">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </a>
                </div>


                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kode Tiket</th>
                                <th>Tarif</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Durasi</th>
                                <th>Status Bayar</th>
                                <th>Status Parkir</th>
                                <th class="text-center" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $index => $record)
                                @php
                                    $entry = \Carbon\Carbon::parse($record->entry_time);
                                    $exit = $record->exit_time ? \Carbon\Carbon::parse($record->exit_time) : now();
                                    $duration = $entry->diff($exit);
                                @endphp
                                <tr>
                                    <td>{{ $records->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $record->ticket_code }}</td>
                                    <td>Rp {{ number_format($record->tarif->rate ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $entry->format('d/m/Y H:i') }}</td>
                                    <td>{{ $record->exit_time ? $exit->format('d/m/Y H:i') : '-' }}</td>
                                    <td>{{ $duration->days * 24 + $duration->h }} jam {{ $duration->i }} mnt</td>
                                    <td>
                                        <span
                                            class="badge {{ $record->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $record->payment_status === 'paid' ? 'Pembayaran Selesai' : 'Menunggu' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $record->status === 'in' ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ $record->status === 'in' ? 'Masih Parkir' : 'Keluar' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.parking-records.show', $record->id) }}"
                                                class="btn btn-sm btn-info text-white" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.parking-records.edit', $record->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                data-id="{{ $record->id }}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">
                                        Belum ada data parkir.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3 d-flex justify-content-end">
                    {{ $records->links() }}
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Pesan session
        @if (session('success'))
            Swal.fire('Berhasil!', @json(session('success')), 'success');
        @endif
        @if (session('error'))
            Swal.fire('Gagal!', @json(session('error')), 'error');
        @endif
        @if ($errors->any())
            Swal.fire('Validasi Gagal', @json($errors->first()), 'error');
        @endif

        // Konfirmasi delete
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data parkir ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/parking-records/${id}`;
                        form.innerHTML = `@csrf @method('DELETE')`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
