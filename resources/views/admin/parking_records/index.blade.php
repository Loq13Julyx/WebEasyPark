@extends('layouts.app')

@section('title', 'Manajemen Data Parkir')

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
                <form method="GET" id="filterForm" class="mb-4">
                    <div class="row g-2 align-items-end">

                        {{-- Tanggal Mulai --}}
                        <div class="col-auto">
                            <label class="form-label fw-semibold small">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ request('start_date') }}"
                                class="form-control form-control-sm" style="width: 180px;">
                        </div>

                        {{-- Tanggal Akhir --}}
                        <div class="col-auto">
                            <label class="form-label fw-semibold small">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ request('end_date') }}"
                                class="form-control form-control-sm" style="width: 180px;">
                        </div>

                        {{-- Status Parkir --}}
                        <div class="col-auto">
                            <label class="form-label fw-semibold small">Status Parkir</label>
                            <select name="status" id="status"
                                class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Semua</option>
                                <option value="in" {{ request('status') == 'in' ? 'selected' : '' }}>
                                    Sedang Parkir
                                </option>
                                <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>
                                    Telah Keluar
                                </option>
                            </select>
                        </div>

                        {{-- Status Pembayaran --}}
                        <div class="col-auto">
                            <label class="form-label fw-semibold small">Status Pembayaran</label>
                            <select name="payment_status" id="payment_status"
                                class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Semua</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                    Pembayaran Selesai
                                </option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                    Menunggu Pembayaran
                                </option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <a href="{{ route('admin.parking-records.index') }}"
                                class="btn btn-sm btn-secondary">
                                Reset
                            </a>
                            <button type="button" class="btn btn-success btn-sm" id="btnPrint">
                                <i class="bi bi-printer"></i> Print
                            </button>
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
                                <th>Slot</th>
                                <th>Tarif</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
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

                                    {{-- SLOT PARKIR --}}
                                    <td>
                                        @if ($record->parkingSlot)
                                            <span class="badge bg-info">
                                                {{ $record->parkingSlot->slot_code ?? $record->parkingSlot->id }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        Rp {{ number_format($record->tarif->rate ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        {{ $record->entry_time }}<br>
                                        <small class="text-muted">
                                            {{ $record->gateIn->name ?? '-' }}
                                        </small>
                                    </td>

                                    <td>
                                        @if ($record->exit_time)
                                            {{ $record->exit_time }}<br>
                                            <small class="text-muted">
                                                {{ $record->gateOut->name ?? '-' }}
                                            </small>
                                        @else
                                            -<br>
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($record->status == 'in')
                                            <span class="badge bg-warning text-dark">
                                                Sedang Parkir
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                Telah Keluar
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($record->payment_status == 'paid')
                                            <span class="badge bg-primary">
                                                Pembayaran Selesai
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Menunggu Pembayaran
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.parking-records.show', $record->id) }}"
                                            class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <button type="button"
                                            class="btn btn-sm btn-danger btn-delete"
                                            data-id="{{ $record->id }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9"
                                        class="text-center text-muted">
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
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = '{{ csrf_token() }}';

            // Tombol Delete
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;

                    Swal.fire({
                        title: 'Hapus data?',
                        text: "Data parkir akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/parking-records/${id}`;
                            form.innerHTML = `
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });

            // Tombol Print
            document.getElementById('btnPrint').addEventListener('click', function() {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                const status = document.getElementById('status').value;
                const paymentStatus = document.getElementById('payment_status').value;

                let printUrl = '{{ route("admin.parking-records.print") }}';
                let params = [];

                if (startDate) params.push('start_date=' + startDate);
                if (endDate) params.push('end_date=' + endDate);
                if (status) params.push('status=' + status);
                if (paymentStatus) params.push('payment_status=' + paymentStatus);

                if (params.length > 0) {
                    printUrl += '?' + params.join('&');
                }

                window.open(printUrl, '_blank');
            });
        });
    </script>
@endsection
