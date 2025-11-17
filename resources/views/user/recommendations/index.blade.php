@extends('layouts.app')

@section('title', 'Rekomendasi Slot Parkir')

@section('content')
<div class="min-h-screen bg-slate-100 py-6 px-4">
    <div class="max-w-7xl mx-auto">

        {{-- SELAMAT DATANG --}}
        <div class="mb-8 bg-white p-6 rounded-xl shadow border border-slate-200 space-y-4">
            <h1 class="text-2xl font-extrabold text-slate-800">
                Selamat Datang di Sistem Parkir EasyPark V2
            </h1>
            <p class="text-slate-600">
                Anda dapat melihat denah slot parkir kampus secara real-time.  
                Slot berwarna hijau menandakan kosong, sedangkan merah menandakan sudah terisi.
            </p>
            <p class="text-slate-500 italic">
                Pilih slot yang tersedia sesuai kebutuhan Anda.
            </p>
        </div>

        {{-- DENAH PARKIR --}}
        <div>
            <div class="bg-white p-6 rounded-xl shadow border border-slate-200 space-y-8">

                @php
                    $areas = $slots->pluck('area.name')->unique();
                @endphp

                @foreach ($areas as $areaName)
                    <div>
                        <h3 class="text-lg font-semibold text-slate-700 mb-4">{{ $areaName }}</h3>
                        <div class="overflow-x-auto">
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-x-12 gap-y-16 justify-center">
                                @foreach ($slots->where('area.name', $areaName) as $slot)
                                    @php
                                        $bg = $slot->status == 'empty'
                                            ? asset('images/parking/slot-empty.png')
                                            : asset('images/parking/slot-occupied.png');
                                    @endphp
                                    <div class="relative h-60 w-36 rounded-xl shadow-md border flex flex-col justify-end items-center text-center overflow-hidden p-3"
                                        style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;">
                                        
                                        <p class="font-bold text-slate-800 text-sm bg-white bg-opacity-60 px-2 rounded mb-1">
                                            {{ $slot->slot_code }}
                                        </p>

                                        <span class="text-xs px-2 py-1 rounded-full font-semibold
                                            @if ($slot->status == 'empty') bg-green-100 text-green-700
                                            @else bg-red-100 text-red-600 @endif mb-1">
                                            {{ strtoupper($slot->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    </div>
</div>
@endsection
