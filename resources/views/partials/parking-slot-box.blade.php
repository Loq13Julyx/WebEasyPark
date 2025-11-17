@php
    // Warna default jika tidak dikirim dari parent
    $color = $color ?? 'blue';

    // Mapping warna untuk area
    $colorSet = [
        'blue' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-700', 'dark' => 'bg-blue-800'],
        'purple' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-700', 'dark' => 'bg-purple-800'],
        'orange' => ['bg' => 'bg-orange-500', 'border' => 'border-orange-700', 'dark' => 'bg-orange-800'],
        'green' => ['bg' => 'bg-green-500', 'border' => 'border-green-700', 'dark' => 'bg-green-800'],
        'red' => ['bg' => 'bg-red-500', 'border' => 'border-red-700', 'dark' => 'bg-red-800'],
        'gray' => ['bg' => 'bg-gray-500', 'border' => 'border-gray-700', 'dark' => 'bg-gray-800'],
    ];

    // Ambil warna, jika tidak ada default ke biru
    $c = $colorSet[$color] ?? $colorSet['blue'];
@endphp

<div class="relative group">

    {{-- Box Slot --}}
    <div
        class="border-4 
        @if ($slot->status == 'empty') border-gray-400 bg-white 
        @else {{ $c['border'] }} {{ $c['bg'] }} @endif
        rounded-lg w-full h-24 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">

        {{-- Slot Kosong --}}
        @if ($slot->status == 'empty')
            <div class="text-center">
                <p class="font-black text-xl text-gray-400">{{ $slot->slot_code }}</p>
                <p class="text-[10px] text-gray-400 font-semibold mt-0.5">KOSONG</p>
            </div>

            {{-- Slot Terisi --}}
        @else
            <div class="text-center">
                <div class="text-3xl">🚗</div>
                <p class="font-bold text-xs text-white {{ $c['dark'] }} px-2 py-0.5 rounded-full mt-1">
                    {{ $slot->slot_code }}
                </p>
            </div>
        @endif
    </div>

    {{-- Garis pembatas --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="h-full border-l-2 border-r-2 border-dashed border-gray-300 rounded-lg"></div>
    </div>

    {{-- Tooltip Distance --}}
    @if (!empty($slot->distance_from_entry))
        <div
            class="absolute -bottom-5 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="text-[10px] text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded whitespace-nowrap">
                📏 {{ $slot->distance_from_entry }}m
            </span>
        </div>
    @endif

</div>
