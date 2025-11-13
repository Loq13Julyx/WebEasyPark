@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Slot Parkir</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.parking-slots.index') }}">Slot Parkir</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mt-3">Form Edit Slot Parkir</h5>

            <form action="{{ route('admin.parking-slots.update', $parking_slot->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Area Parkir --}}
                    <div class="col-md-6">
                        <label for="area_id" class="form-label">
                            Area Parkir <span class="text-danger">*</span>
                        </label>
                        <select name="area_id" id="area_id" class="form-select @error('area_id') is-invalid @enderror">
                            <option value="">-- Pilih Area --</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $parking_slot->area_id) == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih area tempat slot parkir ini berada.</small>
                        @error('area_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kode Slot --}}
                    <div class="col-md-6">
                        <label for="slot_code" class="form-label">
                            Kode Slot <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="slot_code" name="slot_code"
                            value="{{ old('slot_code', $parking_slot->slot_code) }}"
                            class="form-control @error('slot_code') is-invalid @enderror"
                            placeholder="Contoh: A01, B12">
                        <small class="text-muted">Gunakan kode unik untuk slot parkir dalam satu area.</small>
                        @error('slot_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jarak dari Pintu Masuk --}}
                    <div class="col-md-6">
                        <label for="distance_from_entry" class="form-label">
                            Jarak dari Pintu Masuk (meter)
                        </label>
                        <input type="number" step="0.1" id="distance_from_entry" name="distance_from_entry"
                            value="{{ old('distance_from_entry', $parking_slot->distance_from_entry) }}"
                            class="form-control @error('distance_from_entry') is-invalid @enderror"
                            placeholder="Contoh: 12.5">
                        <small class="text-muted">Opsional — isi jika ingin menentukan jarak slot dari pintu masuk.</small>
                        @error('distance_from_entry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status Slot --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">-- Pilih Status --</option>
                            <option value="empty" {{ old('status', $parking_slot->status) == 'empty' ? 'selected' : '' }}>Kosong</option>
                            <option value="occupied" {{ old('status', $parking_slot->status) == 'occupied' ? 'selected' : '' }}>Terisi</option>
                            <option value="inactive" {{ old('status', $parking_slot->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        <small class="text-muted">Perbarui kondisi slot parkir jika ada perubahan.</small>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.parking-slots.index') }}" class="btn btn-secondary me-2">
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
