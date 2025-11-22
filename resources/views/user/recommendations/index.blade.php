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

                    @php
                        function getRouteDirection($areaName)
                        {
                            if (!$areaName) {
                                return 'Dari gate masuk, lurus sedikit → belok kiri → lurus.';
                            }

                            $areaName = strtoupper(trim($areaName));
                            $base = 'Dari gate masuk, lurus sedikit → belok kiri → lurus';

                            if ($areaName === 'AREA A') {
                                return $base . ' → ambil kiri untuk Area A.';
                            }
                            if ($areaName === 'AREA B') {
                                return $base . ' → ambil kanan untuk Area B.';
                            }

                            return $base . '.';
                        }

                        function walkingTime($distance)
                        {
                            return round($distance * 0.8) . ' detik berjalan';
                        }
                    @endphp

                    {{-- Rekomendasi Terbaik --}}
                    <div class="mt-6">
                        <p class="text-xs text-blue-100 uppercase tracking-wider font-medium mb-3">
                            Rekomendasi Terbaik
                        </p>

                        @php $top3 = $recommendedSlots->take(3); @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @forelse ($top3 as $index => $slot)
                                <div
                                    class="flex flex-col gap-3 bg-white/15 backdrop-blur-xl p-4 rounded-xl border border-white/20">

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

                                    <div class="flex items-center gap-1">
                                        <span class="text-sm text-blue-200">📏</span>
                                        <p class="text-xs text-blue-200">
                                            {{ $slot->distance_from_entry }}m dari pintu masuk
                                        </p>
                                    </div>

                                    <div class="flex items-start gap-1">
                                        <span class="text-sm text-blue-200">➡️</span>
                                        <p class="text-xs text-blue-200 leading-relaxed">
                                            {{ $slot->route_direction ?? 'Rute belum tersedia' }}
                                        </p>
                                    </div>

                                </div>
                            @empty
                                <div class="col-span-3 flex items-center gap-4 bg-white/10 p-4 rounded-xl">
                                    <div class="bg-gradient-to-br from-yellow-400/30 to-orange-400/30 p-4 rounded-xl">
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

            {{-- DAFTAR AREA --}}
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

                                    @php
                                        $available = $areaSlots->where('status', 'empty')->count();
                                        $total = $areaSlots->count();
                                        $percentage = $total > 0 ? round(($available / $total) * 100) : 0;
                                    @endphp

                                    <p class="text-xs text-slate-500">
                                        {{ $available }} dari {{ $total }} slot tersedia ({{ $percentage }}%)
                                    </p>
                                </div>
                            </div>

                            <div class="text-sm text-slate-500"></div>
                        </div>

                        @php
                            $top3Codes = $recommendedSlots->take(3)->pluck('slot_code')->toArray();
                        @endphp

                        {{-- Grid Slot --}}
                        <div class="pb-4 overflow-x-auto">
                            <div class="grid grid-cols-6 gap-6 min-w-max">

                                @forelse ($areaSlots as $slot)
                                    @php
                                        $bg =
                                            $slot->status == 'empty'
                                                ? asset('images/parking/slot-empty.png')
                                                : asset('images/parking/slot-occupied.png');

                                        $statusLabel = $slot->status == 'empty' ? 'Tersedia' : 'Terisi';

                                        // Cek apakah slot ini termasuk Top 3
                                        $isRecommended = in_array($slot->slot_code, $top3Codes);
                                    @endphp

                                    <div class="relative h-64 w-40 min-w-[160px] rounded-2xl shadow-lg border-2 transition-all duration-300 hover:scale-105 hover:shadow-xl flex flex-col justify-end items-center text-center overflow-hidden p-4 group
                @if ($slot->status == 'empty') border-green-300 @else border-red-300 @endif"
                                        style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">

                                        {{-- STATUS BAR (ikon rekomendasi di dalam bar) --}}
                                        <div class="absolute bottom-0 left-0 w-full">
                                            <p
                                                class="w-full flex items-center justify-center gap-1 py-1 font-medium text-xs text-white shadow-sm
        @if ($slot->status == 'empty') bg-green-600 @else bg-red-600 @endif">

                                                {{-- ⭐ Ikon hanya jika direkomendasikan --}}
                                                @if ($isRecommended)
                                                    <span class="text-yellow-300 text-sm">⭐</span>
                                                @endif

                                                {{ $slot->slot_code }} | {{ $statusLabel }}
                                            </p>
                                        </div>

                                    </div>
                                @empty
                                    @for ($i = 0; $i < 6; $i++)
                                        <div
                                            class="relative h-64 w-40 min-w-[160px] rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center bg-slate-50">
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
