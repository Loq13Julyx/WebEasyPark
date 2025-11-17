@php
    $occupied = $slot->is_occupied;
@endphp

<div
    class="w-full h-16 flex items-center justify-center
            border border-blue-700 rounded-[4px]
            bg-blue-900 relative">

    {{-- Jika slot terisi tampilkan mobil --}}
    @if ($occupied)
        <img src="{{ asset('images/parking/car.png') }}" class="w-10 absolute inset-0 m-auto opacity-90">
    @endif

    {{-- Nomor slot --}}
    <span class="text-blue-200 text-sm font-bold tracking-wide">
        {{ $slot->code }}
    </span>
</div>
