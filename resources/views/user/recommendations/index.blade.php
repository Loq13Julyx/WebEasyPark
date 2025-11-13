@extends('layouts.app')

@section('title', 'Rekomendasi Slot Parkir')

@section('content')
<div class="flex min-h-screen bg-gray-50 relative">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-white/70"></div>

    {{-- Konten --}}
    <div class="flex-1 flex flex-col items-center py-10 px-6 overflow-y-auto relative z-10">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2">
                🚗 Rekomendasi Slot Parkir
            </h1>
            <p class="text-gray-500 max-w-xl mx-auto">
                Temukan slot parkir terbaik dan terdekat dari pintu masuk.
            </p>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-xl mb-6 shadow-md max-w-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded-xl mb-6 shadow-md max-w-md">{{ session('error') }}</div>
        @endif

        @if(isset($recommendedSlots) && $recommendedSlots->count() > 0)
            @foreach(['A','B','C'] as $areaName)
                @php
                    $areaSlots = $recommendedSlots->filter(fn($slot) => strtolower($slot->area->name ?? '') === strtolower("Area $areaName"));
                    $totalSlots = $areaSlots->count();
                    $availableSlots = $areaSlots->where('status','empty')->count();
                    $filledPercent = $totalSlots > 0 ? round((($totalSlots - $availableSlots)/$totalSlots)*100) : 0;
                    $barColor = $filledPercent < 50 ? 'bg-green-500' : ($filledPercent < 80 ? 'bg-yellow-500' : 'bg-red-500');
                @endphp

                @if($totalSlots > 0)
                    <div class="w-full max-w-7xl mb-10">
                        <div class="mb-4">
                            <h2 class="text-2xl font-bold text-gray-800 mb-1">🅰️ Area {{ $areaName }}</h2>
                            <p class="text-sm text-gray-600 mb-2">{{ $availableSlots }} slot tersedia dari {{ $totalSlots }} total</p>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="h-3 rounded-full {{ $barColor }}" style="width: {{ $filledPercent }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($areaSlots as $slot)
                                <div class="relative bg-white p-6 rounded-3xl shadow-lg hover:shadow-2xl transition border border-gray-200 hover:border-blue-400 overflow-hidden">
                                    <div class="absolute inset-0 bg-center bg-no-repeat bg-contain opacity-10"
                                        style="background-image: url('{{ asset('images/logo-slotparking.png') }}');">
                                    </div>

                                    <div class="relative z-10">
                                        <div class="absolute top-4 right-4">
                                            @if($slot->status == 'empty')
                                                <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full font-semibold flex items-center gap-1">
                                                    <i class="bi bi-check-circle-fill"></i> Tersedia
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded-full font-semibold flex items-center gap-1">
                                                    <i class="bi bi-x-circle-fill"></i> Terisi
                                                </span>
                                            @endif
                                        </div>

                                        <h2 class="font-bold text-xl text-gray-800 mb-3 flex items-center gap-2">
                                            <i class="bi bi-grid-1x2-fill text-blue-500"></i>
                                            Slot {{ $slot->slot_code ?? $slot->name }}
                                        </h2>

                                        <div class="text-sm text-gray-600 space-y-2">
                                            <p>📍 <strong>Area:</strong> {{ $slot->area->name ?? '-' }}</p>
                                            <p>🚙 <strong>Tipe Kendaraan:</strong> {{ $slot->area->vehicleType->name ?? '-' }}</p>
                                            @if($slot->distance_from_entry)
                                                <p>📏 <strong>Jarak:</strong> {{ $slot->distance_from_entry }} m</p>
                                            @endif
                                        </div>

                                        @if($slot->status == 'empty')
                                            <form action="{{ route('user.recommendations.selectSlot', $slot->id) }}" method="POST" class="mt-5 select-slot-form">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm px-4 py-3 rounded-xl w-full flex items-center justify-center gap-2">
                                                    <i class="bi bi-pin-map-fill"></i> Pilih Slot Ini
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl p-6 max-w-md text-center shadow-md">
                🚧 Tidak ada slot parkir yang tersedia saat ini.
            </div>
        @endif
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.select-slot-form').forEach(form => {
    form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin memilih slot ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563EB',
            cancelButtonColor: '#9CA3AF',
            confirmButtonText: 'Ya, pilih slot ini',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if(result.isConfirmed){
                form.submit();
            }
        });
    });
});
</script>
@endsection
