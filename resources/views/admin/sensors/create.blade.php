@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Sensor</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.sensors.index') }}">Sensor</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mt-3">Form Tambah Sensor</h5>

                <form action="{{ route('admin.sensors.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        {{-- Kode Sensor (Auto) --}}
                        <div class="col-md-6">
                            <label for="code" class="form-label">Kode Sensor</label>
                            <input type="text" id="code" name="code" value="{{ $nextCode }}"
                                class="form-control @error('code') is-invalid @enderror" readonly>
                            <small class="text-muted">Kode sensor dibuat otomatis (mis. SEN-01).</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tipe Sensor --}}
                        <div class="col-md-6">
                            <label for="type" class="form-label">Tipe Sensor <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="ultrasonic" {{ old('type', 'ultrasonic') == 'ultrasonic' ? 'selected' : '' }}>
                                    Ultrasonic</option>
                                <option value="infrared" {{ old('type') == 'infrared' ? 'selected' : '' }}>Infrared</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slot (Opsional) --}}
                        <div class="col-md-6">
                            <label for="slot_id" class="form-label">Slot Parkir <small
                                    class="text-muted">(opsional)</small></label>
                            <select name="slot_id" id="slot_id"
                                class="form-select @error('slot_id') is-invalid @enderror">
                                <option value="">-- Tanpa Slot --</option>
                                @foreach ($slots as $slot)
                                    <option value="{{ $slot->id }}" {{ old('slot_id') == $slot->id ? 'selected' : '' }}>
                                        {{ $slot->slot_code }} ({{ $slot->area->name }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Bisa diisi nanti; 1 slot hanya boleh punya 1 sensor.</small>
                            @error('slot_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">-- Pilih Status --</option>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Threshold (cm) --}}
                        <div class="col-md-6">
                            <label for="threshold_cm" class="form-label">Ambang Deteksi (cm)</label>
                            <input type="number" step="0.01" min="0" max="1000" id="threshold_cm"
                                name="threshold_cm" value="{{ old('threshold_cm', 30) }}"
                                class="form-control @error('threshold_cm') is-invalid @enderror">
                            <small class="text-muted">Jarak ≤ nilai ini dianggap TERDETEKSI. Default 30 cm.</small>
                            @error('threshold_cm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Info API Key --}}
                        <div class="col-md-6">
                            <label class="form-label d-block">API Key</label>
                            <input type="text" class="form-control" value="Akan digenerate otomatis saat disimpan"
                                disabled>
                            <small class="text-muted">API key unik dibuat otomatis dan bisa di-rotate di halaman
                                index.</small>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.sensors.index') }}" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
