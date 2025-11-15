@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Manajemen Keluar Parkir</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Kendaraan Masuk</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
                    <h5 class="card-title mb-0">Daftar Kendaraan Sedang Parkir</h5>
                </div>

                {{-- Tabel daftar kendaraan --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kode Tiket</th>
                                <th>Tarif</th>
                                <th>Masuk</th>
                                <th>Durasi</th>
                                <th>Status Parkir</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $index => $record)
                                @php
                                    $entry = \Carbon\Carbon::parse($record->entry_time);
                                    $exit = now();
                                    $duration = $entry->diff($exit);
                                @endphp
                                <tr>
                                    <td>{{ $records->firstItem() + $index }}</td>

                                    {{-- Kode Tiket --}}
                                    <td class="fw-semibold">{{ $record->ticket_code }}</td>

                                    {{-- Tarif --}}
                                    <td>
                                        @if ($record->tarif)
                                            Rp {{ number_format($record->tarif->rate, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Waktu Masuk --}}
                                    <td>{{ $entry->format('d/m/Y H:i') }}</td>

                                    {{-- Durasi --}}
                                    <td>{{ $duration->days * 24 + $duration->h }} jam {{ $duration->i }} mnt</td>

                                    {{-- Status Parkir --}}
                                    <td>
                                        <span class="badge {{ $record->status === 'in' ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ $record->status === 'in' ? 'Masih Parkir' : 'Telah Keluar' }}
                                        </span>
                                    </td>

                                    {{-- Status Pembayaran --}}
                                    <td>
                                        <span
                                            class="badge {{ $record->payment_status === 'paid' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                            {{ $record->payment_status === 'paid' ? 'Pembayaran Selesai' : 'Menunggu Pembayaran' }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td>
                                        @if ($record->status === 'in')
                                            <form action="{{ route('officer.parking-exit.process', $record->id) }}"
                                                method="POST" class="d-inline exit-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-box-arrow-right"></i> Keluarkan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">Sudah Keluar</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">Tidak ada kendaraan yang sedang
                                        parkir.</td>
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

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.exit-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah yakin ingin mengeluarkan kendaraan ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, keluarkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
