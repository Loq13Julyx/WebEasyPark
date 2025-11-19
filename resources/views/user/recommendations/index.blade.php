@extends('layouts.app')

@section('title', 'Rekomendasi Slot Parkir')

@section('content')
    <div class="min-h-screen bg-slate-100 py-6 px-4">
        <div class="max-w-7xl mx-auto">

            {{-- HEADER SELAMAT DATANG --}}
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-8 rounded-2xl shadow-lg text-white">
                    <h1 class="text-3xl font-extrabold mb-2">
                        Selamat Datang 👋
                    </h1>

                    <p class="text-blue-100 text-md leading-relaxed">
                        Temukan slot parkir yang <span class="font-semibold">kosong</span> dan
                        paling <span class="font-semibold">dekat</span> dengan pintu masuk secara cepat & otomatis.
                    </p>

                    {{-- FUNGSIONAL: Saran Rute + Estimasi Waktu --}}
                    {{-- FUNGSIONAL: Saran Rute + Estimasi --}}
                    @php
                        // Rute otomatis berdasarkan area
                        function getRouteDirection($areaName)
                        {
                            if (!$areaName) {
                                return 'Dari gate masuk, lurus sedikit → belok kiri → lurus.';
                            }

                            $areaName = strtoupper(trim($areaName));
                            $base = 'Dari gate masuk, lurus sedikit → belok kiri → lurus';

                            if ($areaName === 'Area A') {
                                return $base . ' → ambil kiri untuk Area A.';
                            }
                            if ($areaName === 'Area B') {
                                return $base . ' → ambil kanan untuk Area B.';
                            }

                            return $base . '.';
                        }

                        // Estimasi waktu jalan
                        function walkingTime($distance)
                        {
                            return round($distance * 0.8) . ' detik berjalan';
                        }
                    @endphp

                    {{-- Rekomendasi Terbaik (Top 3) --}}
                    <div class="mt-6">
                        <p class="text-xs text-blue-100 uppercase tracking-wider font-medium mb-3">
                            Rekomendasi Terbaik
                        </p>

                        @php
                            $top3 = $recommendedSlots->take(3);
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @forelse ($top3 as $index => $slot)
                                <div
                                    class="flex flex-col gap-3 bg-white/15 backdrop-blur-xl p-4 rounded-xl border border-white/20">

                                    {{-- Icon + Header --}}
                                    <div class="flex items-center gap-4">
                                        <div class="bg-gradient-to-br from-emerald-400/30 to-teal-400/30 p-4 rounded-xl">
                                            <span class="text-3xl">⭐</span>
                                        </div>

                                        <div class="flex-1">
                                            <p class="text-xs text-blue-100 uppercase font-semibold mb-1">
                                                Rekomendasi {{ $index + 1 }}
                                            </p>

                                            <p class="text-xl font-bold text-white">
                                                {{ $slot->slot_code }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Jarak --}}
                                    <div class="flex items-center gap-1">
                                        <span class="text-sm text-blue-200">📏</span>
                                        <p class="text-xs text-blue-200">
                                            {{ $slot->distance_from_entry }}m dari pintu masuk
                                        </p>
                                    </div>

                                    {{-- Saran rute --}}
                                    <div class="flex items-start gap-1">
                                        <span class="text-sm text-blue-200">➡️</span>
                                        <p class="text-xs text-blue-200 leading-relaxed">
                                            {{ $slot->route_direction ?? 'Rute belum tersedia' }}
                                        </p>
                                    </div>

                                </div>
                            @empty
                                <div class="col-span-3 flex items-center gap-4 bg-white/10 p-4 rounded-xl">
                                    <div
                                        class="bg-gradient-to-br from-yellow-400/30 to-orange-400/30 p-4 rounded-xl flex-shrink-0">
                                        <span class="text-3xl">⚠️</span>
                                    </div>

                                    <div class="flex-1">
                                        <p class="text-xs text-blue-100 uppercase font-medium mb-1">
                                            Rekomendasi
                                        </p>
                                        <p class="text-sm text-yellow-100 leading-relaxed">
                                            Tidak ada slot yang cocok dengan filter saat ini
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- DENAH PARKIR: loop per area --}}
            <div class="space-y-8">
                @foreach ($areas as $area)
                    @php
                        $areaSlots = $slots->where('area.name', $area->name);
                    @endphp

                    <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 space-y-6">

                        {{-- Header Area --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-2 rounded-lg">
                                    <span class="text-white text-xl">🅿️</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800">{{ $area->name }}</h3>

                                    <p class="text-xs text-slate-500">
                                        {{ $areaSlots->where('status', 'empty')->count() }} dari
                                        {{ $areaSlots->count() }} slot tersedia
                                    </p>
                                </div>
                            </div>

                            <div class="text-sm text-slate-500">
                                {{-- placeholder --}}
                            </div>
                        </div>

                        {{-- Grid Parkir (scroll horizontal untuk mobile) --}}
                        <div class="pb-4 overflow-x-auto">
                            <div class="grid grid-cols-6 gap-6 min-w-max">

                                @forelse ($areaSlots as $slot)
                                    @php
                                        $bg =
                                            $slot->status == 'empty'
                                                ? asset('images/parking/slot-empty.png')
                                                : asset('images/parking/slot-occupied.png');

                                        $statusLabel = $slot->status == 'empty' ? 'Tersedia' : 'Terisi';
                                        $isRecommended = $recommendedSlots->contains('id', $slot->id);
                                    @endphp

                                    <div class="relative h-64 w-40 min-w-[160px] rounded-2xl shadow-lg border-2 transition-all duration-300 hover:scale-105 hover:shadow-xl flex flex-col justify-end items-center text-center overflow-hidden p-4 group
                                        @if ($slot->status == 'empty') border-green-300
                                        @else border-red-300 @endif"
                                        style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">

                                        {{-- Info Slot --}}
                                        <div
                                            class="w-full space-y-2 transform transition-transform group-hover:-translate-y-1">
                                            <p
                                                class="font-bold text-slate-900 text-lg bg-white bg-opacity-90 px-3 py-1.5 rounded-lg shadow-sm backdrop-blur-sm">
                                                {{ $slot->slot_code }}
                                            </p>

                                            <span
                                                class="inline-block text-xs px-3 py-1.5 rounded-full font-semibold shadow-sm
                                                @if ($slot->status == 'empty') bg-green-500 text-white
                                                @else bg-red-500 text-white @endif">
                                                {{ $statusLabel }}
                                            </span>

                                        </div>
                                    </div>
                                @empty
                                    @for ($i = 0; $i < 6; $i++)
                                        <div
                                            class="relative h-64 w-40 min-w-[160px] rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center text-center p-4 bg-slate-50">
                                            <p class="text-xs text-slate-400">Tidak ada slot</p>
                                        </div>
                                    @endfor
                                @endforelse

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <style>
                .content-wrapper {
                    transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
                }

                /* Animasi hanya untuk grid slot */
                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .grid.grid-slot>div {
                    animation: slideIn 0.4s ease-out forwards;
                }

                .grid.grid-slot>div:nth-child(1) {
                    animation-delay: 0.05s;
                }

                .grid.grid-slot>div:nth-child(2) {
                    animation-delay: 0.10s;
                }

                .grid.grid-slot>div:nth-child(3) {
                    animation-delay: 0.15s;
                }

                .grid.grid-slot>div:nth-child(4) {
                    animation-delay: 0.20s;
                }

                .grid.grid-slot>div:nth-child(5) {
                    animation-delay: 0.25s;
                }

                .overflow-x-auto {
                    scroll-behavior: smooth;
                }

                .overflow-x-auto::-webkit-scrollbar {
                    height: 8px;
                }

                .overflow-x-auto::-webkit-scrollbar-track {
                    background: #f1f5f9;
                    border-radius: 10px;
                }

                .overflow-x-auto::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 10px;
                }

                .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }
            </style>

        </div>
    </div>
@endsection
