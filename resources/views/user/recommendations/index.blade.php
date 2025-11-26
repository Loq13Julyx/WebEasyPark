@extends('layouts.app')

@section('title', 'Rekomendasi Slot Parkir')

@section('content')
    <div class="min-h-screen bg-slate-100 py-6 px-4">
        <div class="max-w-7xl mx-auto">

            {{-- HEADER SEDERHANA TANPA REKOMENDASI --}}
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-8 rounded-2xl shadow-lg text-white">
                    <h1 class="text-3xl font-extrabold mb-2">Selamat Datang 👋</h1>

                    <p class="text-blue-100 text-md leading-relaxed">
                        Pilih slot parkir yang kosong dan sesuai kebutuhan Anda.
                    </p>
                </div>
            </div>

            {{-- DAFTAR AREA --}}
            <div class="space-y-8">

                @foreach ($areas as $area)
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 space-y-6">

                        {{-- Header Area --}}
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-2 rounded-lg">
                                <span class="text-white text-xl">🅿️</span>
                            </div>

                            <div>
                                <h3 class="text-xl font-bold text-slate-800">{{ $area->name }}</h3>
                                <p class="text-xs text-slate-500" id="area-info-{{ $area->id }}">
                                    Memuat informasi...
                                </p>
                            </div>
                        </div>

                        {{-- Grid Slot --}}
                        <div class="pb-4 overflow-x-auto">
                            <div class="grid grid-cols-6 gap-6 min-w-max slot-container" id="area-{{ $area->id }}">
                                {{-- AJAX OUTPUT --}}
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>

    {{-- MODAL KONFIRMASI --}}
    <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                    <span class="text-4xl">🅿️</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Konfirmasi Pilihan Slot</h3>
            </div>

            <div class="bg-slate-50 rounded-xl p-4 mb-6 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Kode Slot:</span>
                    <span class="text-lg font-bold text-blue-600" id="modal-slot-code">-</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Area:</span>
                    <span class="text-sm font-bold text-slate-800" id="modal-area-name">-</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Lokasi Area:</span>
                    <span class="text-sm font-medium text-slate-800" id="modal-area-location">-</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Tipe Kendaraan:</span>
                    <span class="text-sm font-medium text-slate-800" id="modal-vehicle-type">-</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Jarak dari Pintu Masuk:</span>
                    <span class="text-sm font-medium text-slate-800" id="modal-distance">-</span>
                </div>
                {{-- TARIF DITAMBAHKAN DI SINI --}}
                <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                    <span class="text-sm text-slate-600 font-semibold">Tarif Parkir:</span>
                    <span class="text-lg font-bold text-green-600" id="modal-tarif">-</span>
                </div>
            </div>

            <div class="flex gap-3">
                <button onclick="cancelSelectSlot()"
                    class="flex-1 px-6 py-3 bg-slate-200 text-slate-700 rounded-xl font-semibold hover:bg-slate-300 transition-colors">
                    Tidak
                </button>
                <button onclick="confirmSelectSlot()"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-600 transition-colors">
                    Ya, Pilih
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL E-KARCIS --}}
    <div id="ticketModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            
            {{-- Area untuk Print --}}
            <div id="printableTicket" class="p-8">
                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <span class="text-4xl">🎫</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-1">E-Karcis Parkir</h3>
                    <p class="text-sm text-slate-500">Simpan atau cetak karcis ini</p>
                </div>

                {{-- Informasi Tiket --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 mb-6 border-2 border-dashed border-blue-300">
                    
                    {{-- Kode Tiket Besar --}}
                    <div class="text-center mb-4 pb-4 border-b-2 border-blue-200">
                        <p class="text-xs text-slate-600 mb-1">Kode Tiket</p>
                        <p class="text-3xl font-bold text-blue-600" id="ticket-code">-</p>
                    </div>

                    {{-- Detail Parkir --}}
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Slot Parkir:</span>
                            <span class="text-sm font-bold text-slate-800" id="ticket-slot">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Area:</span>
                            <span class="text-sm font-bold text-slate-800" id="ticket-area">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Lokasi:</span>
                            <span class="text-sm font-medium text-slate-800" id="ticket-location">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Tipe Kendaraan:</span>
                            <span class="text-sm font-medium text-slate-800" id="ticket-vehicle">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Waktu Masuk:</span>
                            <span class="text-sm font-medium text-slate-800" id="ticket-time">-</span>
                        </div>
                    </div>

                    {{-- Barcode --}}
                    <div class="bg-white rounded-lg p-4 text-center">
                        <canvas id="barcodeCanvas" class="mx-auto"></canvas>
                        <p class="text-xs text-slate-500 mt-2">Tunjukkan barcode ini saat keluar</p>
                    </div>
                </div>

                {{-- Info Footer --}}
                <div class="text-center text-xs text-slate-500 mb-4">
                    <p>Harap simpan karcis ini dengan baik</p>
                    <p>Berlaku untuk 1x masuk dan keluar</p>
                </div>
            </div>

            {{-- Action Buttons (tidak ikut tercetak) --}}
            <div class="px-8 pb-8 flex gap-3 print:hidden">
                <button onclick="printTicket()"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-500 text-white rounded-xl font-semibold hover:from-green-700 hover:to-green-600 transition-colors flex items-center justify-center gap-2">
                    <span>🖨️</span> Cetak
                </button>
                <button onclick="closeTicketModal()"
                    class="flex-1 px-6 py-3 bg-slate-200 text-slate-700 rounded-xl font-semibold hover:bg-slate-300 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- JsBarcode Library untuk generate barcode --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>

    <script>
        let selectedSlotId = null;
        let selectedSlotData = null;

        function loadSlots() {
            $.ajax({
                url: "{{ route('user.recommendations.loadData') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    updateSlotMapUI(res.slots);
                }
            });
        }

        // ========================
        // DENAH SLOT
        // ========================
        function updateSlotMapUI(data) {

            $(".slot-container").empty();
            $("[id^='area-info-']").html("Memuat...");

            const stats = {};

            data.forEach(slot => {
                if (!stats[slot.area_id]) {
                    stats[slot.area_id] = {
                        total: 0,
                        empty: 0
                    };
                }
                stats[slot.area_id].total++;
                if (slot.status === "empty") stats[slot.area_id].empty++;
            });

            for (let id in stats) {
                let s = stats[id];
                let p = Math.round((s.empty / s.total) * 100);
                $(`#area-info-${id}`).html(`${s.empty} dari ${s.total} slot tersedia (${p}%)`);
            }

            data.forEach(slot => {
                const bg = slot.status === "empty" ?
                    "/images/parking/slot-empty.png" :
                    "/images/parking/slot-occupied.png";

                const label = slot.status === "empty" ? "Tersedia" : "Terisi";
                const clickable = slot.status === "empty" ? "cursor-pointer" : "cursor-not-allowed opacity-70";
                const clickHandler = slot.status === "empty" ?
                    `onclick='showConfirmModal(${JSON.stringify(slot).replace(/'/g, "&#39;")})'` :
                    "";

                $(`#area-${slot.area_id}`).append(`
                <div class="relative h-64 w-40 min-w-[160px] rounded-2xl shadow-lg border-2 
                    transition-all duration-300 hover:scale-105 hover:shadow-xl flex flex-col justify-end items-center 
                    overflow-hidden p-4 ${clickable}"
                    ${clickHandler}
                    style="background-image:url('${bg}'); background-size:cover; background-position:center;">

                    <div class="absolute bottom-0 left-0 w-full">
                        <p class="w-full flex items-center justify-center gap-1 py-1 font-medium text-xs text-white 
                            ${slot.status === 'empty' ? 'bg-green-600' : 'bg-red-600'}">
                            ${slot.slot_code} | ${label}
                        </p>
                    </div>
                </div>
            `);
            });
        }

        // ========================
        // MODAL KONFIRMASI
        // ========================
        function showConfirmModal(slotData) {
            selectedSlotId = slotData.id;
            selectedSlotData = slotData;

            $("#modal-slot-code").text(slotData.slot_code || '-');
            $("#modal-area-name").text(slotData.area_name || '-');
            $("#modal-area-location").text(slotData.area_location || '-');
            $("#modal-vehicle-type").text(slotData.vehicle_type || '-');

            const distance = slotData.distance_from_entry;
            $("#modal-distance").text(distance ? Math.round(distance) + ' m' : '-');

            // Format tarif sebagai Rupiah
            const tarif = slotData.tarif_rate || 0;
            const formattedTarif = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(tarif);
            $("#modal-tarif").text(formattedTarif);

            $("#confirmModal").removeClass("hidden");
        }

        function cancelSelectSlot() {
            selectedSlotId = null;
            selectedSlotData = null;
            $("#confirmModal").addClass("hidden");
        }

        function confirmSelectSlot() {
            if (!selectedSlotId) return;

            // Cari tarif_id berdasarkan vehicle_type_id dari area
            let tarifId = null;
            if (selectedSlotData.vehicle_type_id) {
                tarifId = selectedSlotData.vehicle_type_id;
            }

            $.ajax({
                url: "{{ route('user.recommendations.selectSlot') }}",
                method: "POST",
                data: {
                    slot_id: selectedSlotId,
                    tarif_id: tarifId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    $("#confirmModal").addClass("hidden");

                    if (res.success) {
                        showTicketModal(res, selectedSlotData);
                        setTimeout(loadSlots, 1000);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Silakan coba lagi'));
                    $("#confirmModal").addClass("hidden");
                }
            });
        }

        // ========================
        // MODAL E-KARCIS
        // ========================
        function showTicketModal(responseData, slotData) {
            // Set data tiket
            $("#ticket-code").text(responseData.ticket_code);
            $("#ticket-slot").text(responseData.slot_code);
            $("#ticket-area").text(slotData.area_name || '-');
            $("#ticket-location").text(slotData.area_location || '-');
            $("#ticket-vehicle").text(slotData.vehicle_type || '-');
            
            // Format waktu sekarang
            const now = new Date();
            const timeString = now.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            $("#ticket-time").text(timeString);

            // Generate barcode
            try {
                JsBarcode("#barcodeCanvas", responseData.ticket_code, {
                    format: "CODE128",
                    width: 2,
                    height: 80,
                    displayValue: true,
                    fontSize: 14,
                    margin: 10
                });
            } catch (e) {
                console.error("Error generating barcode:", e);
            }

            // Tampilkan modal
            $("#ticketModal").removeClass("hidden");
        }

        function closeTicketModal() {
            $("#ticketModal").addClass("hidden");
            selectedSlotData = null;
        }

        function printTicket() {
            // Simpan judul asli
            const originalTitle = document.title;
            
            // Ubah judul untuk print
            document.title = 'E-Karcis Parkir - ' + $("#ticket-code").text();
            
            // Cetak
            window.print();
            
            // Kembalikan judul
            document.title = originalTitle;
        }

        // ========================
        // AUTO REFRESH
        // ========================
        setInterval(loadSlots, 5000);

        $(document).ready(loadSlots);
    </script>

    {{-- Custom CSS untuk Print --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableTicket, #printableTicket * {
                visibility: visible;
            }
            #printableTicket {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .print\:hidden {
                display: none !important;
            }
        }
    </style>
@endsection