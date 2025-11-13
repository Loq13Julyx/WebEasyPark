@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Manajemen Sensor</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Sensor</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">

            {{-- Header (tanpa filter) --}}
            <div class="d-flex justify-content-between align-items-center mb-3 mt-3 flex-wrap gap-2">
                <h5 class="card-title mb-0">Daftar Sensor</h5>
                <a href="{{ route('admin.sensors.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Sensor
                </a>
            </div>

            {{-- Tabel daftar sensor --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Area</th>
                            <th>Slot</th>
                            <th class="text-center">Threshold (cm)</th>
                            <th class="text-center">Jarak Terakhir (cm)</th>
                            <th class="text-center">Detected</th>
                            <th class="text-nowrap">Update Terakhir</th>
                            <th class="text-center">API Key</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sensors as $index => $sensor)
                            <tr>
                                <td>{{ $sensors->firstItem() + $index }}</td>
                                <td class="fw-semibold">{{ $sensor->code }}</td>
                                <td>{{ ucfirst($sensor->type) }}</td>
                                <td>
                                    @if ($sensor->status === 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>{{ $sensor->slot->area->name ?? '-' }}</td>
                                <td>{{ $sensor->slot->slot_code ?? '-' }}</td>
                                <td class="text-center">{{ $sensor->threshold_cm ?? '-' }}</td>
                                <td class="text-center">{{ $sensor->last_distance_cm ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($sensor->last_detected)
                                        <span class="badge bg-danger">TERISI</span>
                                    @else
                                        <span class="badge bg-success">KOSONG</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    {{ $sensor->last_update ? $sensor->last_update->format('Y-m-d H:i:s') : '-' }}
                                </td>

                                {{-- API Key (mask & copy) --}}
                                <td class="text-center">
                                    @php
                                        $masked = $sensor->api_key ? substr($sensor->api_key, 0, 8) . '••••' : '-';
                                    @endphp
                                    <div class="d-flex gap-1 justify-content-center">
                                        <span class="small text-monospace">{{ $masked }}</span>
                                        @if ($sensor->api_key)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary btn-copy-key"
                                                data-key="{{ $sensor->api_key }}" title="Copy API Key">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.sensors.edit', $sensor->id) }}"
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- Regenerate API key (pastikan route tersedia) --}}
                                        <form method="POST" action="{{ route('admin.sensors.regen-key', $sensor->id) }}"
                                              onsubmit="return confirm('Generate API Key baru untuk {{ $sensor->code }}?');">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-primary" type="submit" title="Regenerate API Key">
                                                <i class="bi bi-key"></i>
                                            </button>
                                        </form>

                                        {{-- Hapus --}}
                                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                            data-id="{{ $sensor->id }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">Belum ada data sensor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $sensors->links() }}
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
        Swal.fire({ icon:'success', title:'Berhasil!', text:@json(session('success')), confirmButtonText:'OK' });
    @endif
    @if (session('error'))
        Swal.fire({ icon:'error', title:'Gagal!', text:@json(session('error')), confirmButtonText:'OK' });
    @endif
    @if ($errors->any())
        Swal.fire({ icon:'error', title:'Validasi Gagal', text:@json($errors->first()), confirmButtonText:'OK' });
    @endif

    // Copy API Key
    document.querySelectorAll('.btn-copy-key').forEach(btn => {
        btn.addEventListener('click', async () => {
            const key = btn.dataset.key;
            try {
                await navigator.clipboard.writeText(key);
                Swal.fire({ icon:'success', title:'Tersalin', text:'API Key disalin ke clipboard.' });
            } catch (e) {
                Swal.fire({ icon:'error', title:'Gagal', text:'Tidak bisa menyalin API Key.' });
            }
        });
    });

    // Konfirmasi hapus sensor
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            let sensorId = this.dataset.id;

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data sensor akan dihapus!",
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
                    form.action = "{{ url('/admin/sensors') }}/" + sensorId;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        });
    });
</script>
@endsection
