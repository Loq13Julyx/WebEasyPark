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

                    {{-- Informasi Rekomendasi --}}
                    <div class="mt-6">
                        @php
                            $jumlahTersedia = $slots->where('status', 'empty')->count();
                            $slotRekom = $recommendedSlots->first();
                        @endphp

                        <div class="bg-white/15 backdrop-blur-xl rounded-xl border border-white/20 p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                {{-- Slot Tersedia --}}
                                <div class="flex items-center gap-4">
                                    <div class="bg-white/20 p-4 rounded-xl flex-shrink-0">
                                        <span class="text-4xl">✅</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-blue-100 uppercase tracking-wider font-medium mb-1">
                                            Slot Tersedia
                                        </p>
                                        <p class="text-4xl font-bold">{{ $jumlahTersedia }}</p>
                                        <p class="text-xs text-blue-200 mt-1">slot parkir kosong</p>
                                    </div>
                                </div>

                                {{-- Rekomendasi Terbaik --}}
                                @if ($slotRekom)
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="bg-gradient-to-br from-emerald-400/30 to-teal-400/30 p-4 rounded-xl flex-shrink-0">
                                            <span class="text-4xl">⭐</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs text-blue-100 uppercase tracking-wider font-medium mb-1">
                                                Rekomendasi Terbaik
                                            </p>
                                            <p class="text-3xl font-bold">{{ $slotRekom->slot_code }}</p>
                                            <div class="flex items-center gap-1.5 mt-1.5">
                                                <span class="text-sm text-blue-200">📏</span>
                                                <p class="text-xs text-blue-200">
                                                    {{ $slotRekom->distance_from_entry }}m dari pintu masuk
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="bg-gradient-to-br from-yellow-400/30 to-orange-400/30 p-4 rounded-xl flex-shrink-0">
                                            <span class="text-4xl">⚠️</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs text-blue-100 uppercase tracking-wider font-medium mb-1">
                                                Rekomendasi
                                            </p>
                                            <p class="text-sm text-yellow-100 leading-relaxed">
                                                Tidak ada slot yang cocok dengan filter saat ini
                                            </p>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DENAH PARKIR --}}
            <div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 space-y-10">

                    @php
                        $areas = $slots->pluck('area.name')->unique();
                    @endphp

                    @foreach ($areas as $areaName)
                        <div>
                            {{-- Header Area --}}
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-2 rounded-lg">
                                    <span class="text-white text-xl">🅿️</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800">{{ $areaName }}</h3>
                                    <p class="text-xs text-slate-500">
                                        {{ $slots->where('area.name', $areaName)->where('status', 'empty')->count() }} dari
                                        {{ $slots->where('area.name', $areaName)->count() }} slot tersedia
                                    </p>
                                </div>
                            </div>

                            {{-- Grid Parkir --}}
                            <div class="overflow-x-auto pb-4">
                                <div
                                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-6 min-w-max">
                                    @foreach ($slots->where('area.name', $areaName) as $slot)
                                        @php
                                            $bg =
                                                $slot->status == 'empty'
                                                    ? asset('images/parking/slot-empty.png')
                                                    : asset('images/parking/slot-occupied.png');

                                            $statusLabel = $slot->status == 'empty' ? 'Tersedia' : 'Terisi';
                                            $isRecommended = $recommendedSlots->contains('id', $slot->id);
                                        @endphp

                                        <div class="relative h-64 w-40 rounded-2xl shadow-lg border-2 transition-all duration-300 hover:scale-105 hover:shadow-xl flex flex-col justify-end items-center text-center overflow-hidden p-4 group
                                @if ($isRecommended) border-yellow-400 ring-2 ring-yellow-300 ring-offset-2
                                @elseif ($slot->status == 'empty') border-green-300
                                @else border-red-300 @endif"
                                            style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">

                                            {{-- Badge Rekomendasi --}}
                                            @if ($isRecommended)
                                                <div
                                                    class="absolute top-3 right-3 bg-gradient-to-r from-yellow-400 to-orange-400 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg flex items-center gap-1 animate-pulse">
                                                    <span>⭐</span>
                                                    <span>Top</span>
                                                </div>
                                            @endif

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

                                                {{-- Jarak dari pintu masuk --}}
                                                <p
                                                    class="text-xs text-slate-600 bg-white bg-opacity-80 px-2 py-1 rounded-md backdrop-blur-sm">
                                                    📏 {{ $slot->distance_from_entry }}m
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- Animasi CSS untuk transisi smooth saat sidebar dibuka/tutup --}}
            <style>
                /* Transisi smooth untuk content area */
                .content-wrapper {
                    transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
                }

                /* Animasi untuk card parkir */
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

                .grid>div {
                    animation: slideIn 0.4s ease-out forwards;
                }

                .grid>div:nth-child(1) {
                    animation-delay: 0.05s;
                }

                .grid>div:nth-child(2) {
                    animation-delay: 0.1s;
                }

                .grid>div:nth-child(3) {
                    animation-delay: 0.15s;
                }

                .grid>div:nth-child(4) {
                    animation-delay: 0.2s;
                }

                .grid>div:nth-child(5) {
                    animation-delay: 0.25s;
                }

                /* Smooth scroll horizontal */
                .overflow-x-auto {
                    scroll-behavior: smooth;
                }

                /* Custom scrollbar */
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
