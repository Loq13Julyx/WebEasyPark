@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Edit Sensor</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.sensors.index') }}">Sensor</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mt-3">Form Edit Sensor</h5>

                <form action="{{ route('admin.sensors.update', $sensor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Kode Sensor --}}
                        <div class="col-md-6">
                            <label for="code" class="form-label">Kode Sensor <span class="text-danger">*</span></label>
                            <input type="text" id="code" name="code" value="{{ old('code', $sensor->code) }}"
                                class="form-control @error('code') is-invalid @enderror">
                            <small class="text-muted">Kode sensor unik (mis. SEN-01).</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tipe Sensor --}}
                        <div class="col-md-6">
                            <label for="type" class="form-label">Tipe Sensor <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="ultrasonic" {{ old('type', $sensor->type) == 'ultrasonic' ? 'selected' : '' }}>
                                    Ultrasonic</option>
                                <option value="infrared" {{ old('type', $sensor->type) == 'infrared' ? 'selected' : '' }}>Infrared
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slot Parkir (opsional) --}}
                        <div class="col-md-6">
                            <label for="slot_id" class="form-label">Slot Parkir <small
                                    class="text-muted">(opsional)</small></label>
                            <select name="slot_id" id="slot_id"
                                class="form-select @error('slot_id') is-invalid @enderror">
                                <option value="">-- Tanpa Slot --</option>
                                @foreach ($slots as $slot)
                                    <option value="{{ $slot->id }}"
                                        {{ old('slot_id', $sensor->slot_id) == $slot->id ? 'selected' : '' }}
                                        @if ($slot->sensor && $slot->id != $sensor->slot_id) disabled @endif>
                                        {{ $slot->slot_code }} ({{ $slot->area->name }})
                                        @if ($slot->sensor && $slot->id != $sensor->slot_id)
                                            - Sudah ada sensor
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Satu slot hanya boleh punya satu sensor.</small>
                            @error('slot_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Sensor --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">-- Pilih Status --</option>
                                <option value="active" {{ old('status', $sensor->status) == 'active' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="inactive" {{ old('status', $sensor->status) == 'inactive' ? 'selected' : '' }}>
                                    Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Threshold (cm) --}}
                        <div class="col-md-6">
                            <label for="threshold_cm" class="form-label">Ambang Deteksi (cm)</label>
                            <input type="number" step="0.01" min="0" max="1000" id="threshold_cm"
                                name="threshold_cm" value="{{ old('threshold_cm', $sensor->threshold_cm ?? 30) }}"
                                class="form-control @error('threshold_cm') is-invalid @enderror">
                            <small class="text-muted">Jarak ≤ nilai ini dianggap TERDETEKSI.</small>
                            @error('threshold_cm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- API Key (readonly + copy + regenerate) --}}
                        <div class="col-md-6">
                            <label class="form-label">API Key</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $sensor->api_key }}" readonly
                                    id="apiKeyInput">
                                <button class="btn btn-outline-secondary" type="button" id="btnCopyKey" title="Copy">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.sensors.regen-key', $sensor->id) }}"
                                    onsubmit="return confirm('Generate API Key baru untuk {{ $sensor->code }}?');">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-outline-primary" type="submit" title="Regenerate">
                                        <i class="bi bi-key"></i>
                                    </button>
                                </form>
                            </div>
                            <small class="text-muted">API key digunakan oleh gateway Flask saat kirim data.</small>
                        </div>

                        {{-- Info terakhir (readonly) --}}
                        <div class="col-md-6">
                            <label class="form-label">Jarak Terakhir (cm)</label>
                            <input type="text" class="form-control" value="{{ $sensor->last_distance_cm ?? '-' }}"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Update Terakhir</label>
                            <input type="text" class="form-control"
                                value="{{ $sensor->last_update ? $sensor->last_update->format('Y-m-d H:i:s') : '-' }}"
                                readonly>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.sensors.index') }}" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.getElementById('btnCopyKey')?.addEventListener('click', async () => {
            const el = document.getElementById('apiKeyInput');
            try {
                await navigator.clipboard.writeText(el.value);
                alert('API Key disalin ke clipboard.');
            } catch (e) {
                alert('Gagal menyalin API Key.');
            }
        });
    </script>
@endsection
