@extends('layouts.app')

@section('title', 'Rekomendasi Slot Parkir')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 py-6 px-4">

    <div class="max-w-[1400px] mx-auto">
        
        {{-- Header --}}
        <div class="text-center mb-6">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2">
                🅿️ Layout Parkir Landscape
            </h1>
            <p class="text-gray-600 text-sm">
                Tampilan real-time area parkir dari atas (Top View)
            </p>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded-xl mb-4 shadow-md max-w-md mx-auto text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded-xl mb-4 shadow-md max-w-md mx-auto text-sm">{{ session('error') }}</div>
        @endif

        {{-- Statistik --}}
        @if(isset($recommendedSlots) && $recommendedSlots->count() > 0)
            @php
                $totalAvailable = $recommendedSlots->where('status','empty')->count();
                $totalAll = $recommendedSlots->count();
            @endphp
            <div class="bg-white rounded-xl shadow-lg p-4 mb-6 border-2 border-gray-300">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Status Keseluruhan</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalAvailable }}/{{ $totalAll }}</p>
                        <p class="text-xs text-gray-500">Slot Tersedia</p>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-white border-4 border-gray-400 rounded flex items-center justify-center">
                                <span class="text-xs font-bold text-gray-400">A1</span>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Kosong</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-500 border-4 border-blue-700 rounded flex items-center justify-center text-lg">🚗</div>
                            <span class="text-sm font-medium text-gray-700">Terisi</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Parking Layout (Landscape) --}}
        @if(isset($recommendedSlots) && $recommendedSlots->count() > 0)

            @php
                $areaASlots = $recommendedSlots->filter(fn($slot) => stripos($slot->area->name ?? '', 'A') !== false)->take(5);
                $areaBSlots = $recommendedSlots->filter(fn($slot) => stripos($slot->area->name ?? '', 'B') !== false)->take(5);
            @endphp

            <div class="bg-white rounded-2xl shadow-2xl p-6 border-4 border-gray-300 max-w-4xl mx-auto">
                
                {{-- Hanya kolom tengah (area parkir) --}}
                <div class="space-y-6">

                    {{-- AREA A --}}
                    <div>
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center py-2 rounded-lg font-bold shadow-lg mb-3">
                            🅰️ AREA A
                        </div>

                        <div class="grid grid-cols-5 gap-3">
                            @foreach($areaASlots as $index => $slot)
                                <div class="relative group">

                                    <div class="border-4 
                                        @if($slot->status == 'empty') border-gray-400 bg-white 
                                        @else border-blue-700 bg-blue-500 
                                        @endif 
                                        rounded-lg w-full h-24 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">
                                        
                                        @if($slot->status == 'empty')
                                            <div class="text-center">
                                                <p class="font-black text-xl text-gray-400">{{ $slot->slot_code ?? 'A'.($index+1) }}</p>
                                                <p class="text-[10px] text-gray-400 font-semibold mt-0.5">KOSONG</p>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <div class="text-3xl">🚗</div>
                                                <p class="font-bold text-xs text-white bg-blue-800 px-2 py-0.5 rounded-full mt-1">{{ $slot->slot_code ?? 'A'.($index+1) }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="absolute inset-0 pointer-events-none">
                                        <div class="h-full border-l-2 border-r-2 border-dashed border-gray-300 rounded-lg"></div>
                                    </div>

                                    @if($slot->distance_from_entry)
                                        <div class="absolute -bottom-5 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-[10px] text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded whitespace-nowrap">
                                                📏 {{ $slot->distance_from_entry }}m
                                            </span>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Jalan Tengah --}}
                    <div class="relative py-4">
                        <div class="border-y-4 border-dashed border-gray-300 h-16 flex items-center justify-center">
                            <p class="text-gray-400 font-bold text-sm"></p>
                        </div>
                    </div>

                    {{-- AREA B --}}
                    <div>
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white text-center py-2 rounded-lg font-bold shadow-lg mb-3">
                            🅱️ AREA B
                        </div>

                        <div class="grid grid-cols-5 gap-3">
                            @foreach($areaBSlots as $index => $slot)
                                <div class="relative group">

                                    <div class="border-4 
                                        @if($slot->status == 'empty') border-gray-400 bg-white 
                                        @else border-purple-700 bg-purple-500 
                                        @endif 
                                        rounded-lg w-full h-24 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">

                                        @if($slot->status == 'empty')
                                            <div class="text-center">
                                                <p class="font-black text-xl text-gray-400">{{ $slot->slot_code ?? 'B'.($index+1) }}</p>
                                                <p class="text-[10px] text-gray-400 font-semibold mt-0.5">KOSONG</p>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <div class="text-3xl">🚗</div>
                                                <p class="font-bold text-xs text-white bg-purple-800 px-2 py-0.5 rounded-full mt-1">{{ $slot->slot_code ?? 'B'.($index+1) }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="absolute inset-0 pointer-events-none">
                                        <div class="h-full border-l-2 border-r-2 border-dashed border-gray-300 rounded-lg"></div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>

            {{-- AREA C (jika ada) --}}
            @php
                $areaCSlots = $recommendedSlots->filter(fn($slot) => stripos($slot->area->name ?? '', 'C') !== false)->take(5);
            @endphp
            @if($areaCSlots->count() > 0)
            <div class="mt-6 bg-white rounded-2xl shadow-2xl p-6 border-4 border-gray-300 max-w-3xl mx-auto">
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white text-center py-2 rounded-lg font-bold shadow-lg mb-4">
                    🅲 AREA C (Area Tambahan)
                </div>
                
                <div class="grid grid-cols-5 gap-3">
                    @foreach($areaCSlots as $index => $slot)
                        <div class="relative group">
                            <div class="border-4 
                                @if($slot->status == 'empty') border-gray-400 bg-white 
                                @else border-orange-700 bg-orange-500 
                                @endif 
                                rounded-lg h-24 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">

                                @if($slot->status == 'empty')
                                    <div class="text-center">
                                        <p class="font-black text-xl text-gray-400">{{ $slot->slot_code ?? 'C'.($index+1) }}</p>
                                        <p class="text-[10px] text-gray-400 font-semibold mt-0.5">KOSONG</p>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="text-3xl">🚗</div>
                                        <p class="font-bold text-xs text-white bg-orange-800 px-2 py-0.5 rounded-full mt-1">{{ $slot->slot_code ?? 'C'.($index+1) }}</p>
                                    </div>
                                @endif

                            </div>

                            <div class="absolute inset-0 pointer-events-none">
                                <div class="h-full border-l-2 border-r-2 border-dashed border-gray-300 rounded-lg"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        @else
            <div class="bg-yellow-50 border-4 border-yellow-300 text-yellow-800 rounded-2xl p-8 text-center shadow-xl">
                <p class="text-6xl mb-4">🚧</p>
                <p class="text-xl font-bold">Tidak ada slot parkir yang tersedia saat ini.</p>
            </div>
        @endif

        <div class="mt-6 text-center text-gray-600 text-xs">
            <p>💡 Layout parkir landscape - Hover untuk melihat jarak dari pintu masuk</p>
        </div>

    </div>

</div>
@endsection
