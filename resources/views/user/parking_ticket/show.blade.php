@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-12 px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
            {{-- Header Tiket --}}
            <div class="bg-indigo-600 text-white text-center py-6 px-4">
                <h1 class="text-3xl font-bold tracking-wide">TIKET PARKIR</h1>
                <p class="text-sm opacity-90 mt-1">Simpan tiket ini untuk keluar dari area parkir</p>
            </div>

            {{-- Isi Tiket --}}
            <div class="p-8 flex flex-col md:flex-row items-center md:items-start gap-8">
                {{-- QR Code --}}
                <div class="flex flex-col items-center justify-center w-full md:w-1/3">
                    <div class="bg-gray-100 p-4 rounded-xl shadow-inner">
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code Tiket Parkir"
                            class="w-44 h-44 object-contain mb-3">
                    </div>
                    <span class="text-gray-500 text-xs mt-2 italic">
                        Scan untuk konfirmasi tiket
                    </span>
                </div>

                {{-- Info Tiket --}}
                <div class="flex-1 w-full">
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h2 class="text-lg text-gray-600">Kode Tiket</h2>
                        <p class="text-2xl font-bold text-indigo-700 tracking-wide">
                            {{ $record->ticket_code }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                        <div>
                            <p class="text-sm text-gray-500">Slot Parkir</p>
                            <p class="font-semibold text-gray-800">{{ $record->parkingSlot->slot_code ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tipe Kendaraan</p>
                            <p class="font-semibold text-gray-800">{{ $record->vehicleType->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tarif Awal</p>
                            <p class="font-semibold text-gray-800">
                                Rp {{ number_format($record->tarif->initial_rate ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Waktu Masuk</p>
                            <p class="font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($record->entry_time)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status Pembayaran</p>
                            <p
                                class="font-semibold {{ $record->payment_status === 'paid' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $record->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status Parkir</p>
                            <p class="font-semibold {{ $record->status === 'in' ? 'text-blue-600' : 'text-gray-600' }}">
                                {{ strtoupper($record->status) }}
                            </p>
                        </div>
                    </div>

                    {{-- Tombol kembali ke rekomendasi --}}
                    <div class="mt-6 text-center">
                        <a href="{{ route('user.recommendations.index') }}"
                            class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl transition">
                            ← Kembali ke Rekomendasi Slot
                        </a>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 border-t border-gray-200 py-4 text-center text-sm text-gray-500">
                <p class="italic">Tunjukkan QR Code ini saat keluar dari area parkir</p>
                <p class="mt-1 text-gray-400">© {{ date('Y') }} EasyPark System</p>
            </div>
        </div>
    </div>
@endsection
