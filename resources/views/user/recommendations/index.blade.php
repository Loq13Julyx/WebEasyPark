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
                {{-- TARIF --}}
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

    {{-- MODAL E-KARCIS PROFESIONAL DENGAN BARCODE SESUAI UKURAN --}}
    <div id="ticketModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 relative">

            {{-- Header --}}
            <div class="text-center mb-4">
                <h3 class="text-2xl font-bold text-indigo-600">E-Karcis Parkir</h3>
                <p class="text-sm text-gray-400 mt-1">Tiket Masuk Kendaraan</p>
            </div>

            {{-- Info Slot --}}
            <div class="space-y-3 text-gray-700 border-t border-b border-gray-200 py-3">
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-500">Slot Parkir:</span>
                    <span id="ticket-slot" class="font-bold text-indigo-600 text-lg">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-500">Area:</span>
                    <span id="ticket-area" class="font-medium">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-500">Lokasi:</span>
                    <span id="ticket-location" class="font-medium">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-500">Tipe Kendaraan:</span>
                    <span id="ticket-vehicle" class="font-medium">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-500">Waktu Masuk:</span>
                    <span id="ticket-time" class="font-medium">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-500">Tarif:</span>
                    <span id="ticket-price" class="font-medium text-green-600">-</span>
                </div>
            </div>

            {{-- Barcode --}}
            <div class="mt-4 text-center">
                <svg id="barcodeCanvas" class="mx-auto" style="width:90%; height:80px;"></svg>
            </div>

            {{-- Footer --}}
            <div class="mt-4 text-center text-xs text-gray-400">
                ⚠️ Simpan karcis ini dengan baik • Berlaku untuk 1x masuk dan keluar
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-4 flex gap-3">
                <button onclick="printTicket()"
                    class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-2xl shadow hover:bg-indigo-700 transition-all">
                    🖨️ Cetak
                </button>
                <button onclick="closeTicketModal()"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-2xl shadow hover:bg-gray-300 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
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

        function updateSlotMapUI(data) {
            $(".slot-container").empty();
            $("[id^='area-info-']").html("Memuat...");

            const stats = {};

            // Hitung statistik per area (3 status)
            data.forEach(slot => {
                if (!stats[slot.area_id]) stats[slot.area_id] = {
                    total: 0,
                    empty: 0,
                    reserved: 0,
                    occupied: 0
                };
                stats[slot.area_id].total++;
                if (slot.status === "empty") stats[slot.area_id].empty++;
                if (slot.status === "reserved") stats[slot.area_id].reserved++;
                if (slot.status === "occupied") stats[slot.area_id].occupied++;
            });

            // Update info area dengan 3 status
            for (let id in stats) {
                let s = stats[id];
                $(`#area-info-${id}`).html(`
                    <span class="text-green-600 font-semibold">${s.empty} Tersedia</span> • 
                    <span class="text-yellow-600 font-semibold">${s.reserved} Terpesan</span> • 
                    <span class="text-red-600 font-semibold">${s.occupied} Terisi</span> • 
                    <span class="text-slate-600">Total: ${s.total}</span>
                `);
            }

            // Render slot cards dengan 3 status
            data.forEach(slot => {
                let bg, label, clickable, clickHandler, badgeColor;

                if (slot.status === "empty") {
                    bg = "/images/parking/slot-empty.png";
                    label = "Tersedia";
                    clickable = "cursor-pointer";
                    clickHandler = `onclick='showConfirmModal(${JSON.stringify(slot).replace(/'/g, "&#39;")})'`;
                    badgeColor = "bg-green-600";
                } else if (slot.status === "reserved") {
                    bg = "/images/parking/slot-reserved.png";
                    label = "Terpesan";
                    clickable = "cursor-not-allowed opacity-70";
                    clickHandler = "";
                    badgeColor = "bg-yellow-600";
                } else { // occupied
                    bg = "/images/parking/slot-occupied.png";
                    label = "Terisi";
                    clickable = "cursor-not-allowed opacity-70";
                    clickHandler = "";
                    badgeColor = "bg-red-600";
                }

                $(`#area-${slot.area_id}`).append(`
                    <div class="relative h-64 w-40 min-w-[160px] rounded-2xl shadow-lg border-2 
                        transition-all duration-300 hover:scale-105 hover:shadow-xl flex flex-col justify-end items-center 
                        overflow-hidden p-4 ${clickable}"
                        ${clickHandler}
                        style="background-image:url('${bg}'); background-size:cover; background-position:center;">

                        <div class="absolute bottom-0 left-0 w-full">
                            <p class="w-full flex items-center justify-center gap-1 py-1 font-medium text-xs text-white ${badgeColor}">
                                ${slot.slot_code} | ${label}
                            </p>
                        </div>
                    </div>
                `);
            });
        }

        function showConfirmModal(slotData) {
            selectedSlotId = slotData.id;
            selectedSlotData = slotData;

            $("#modal-slot-code").text(slotData.slot_code || '-');
            $("#modal-area-name").text(slotData.area_name || '-');
            $("#modal-area-location").text(slotData.area_location || '-');
            $("#modal-vehicle-type").text(slotData.vehicle_type || '-');

            const distance = slotData.distance_from_entry;
            $("#modal-distance").text(distance ? Math.round(distance) + ' m' : '-');

            const tarif = slotData.tarif_rate || 0;
            $("#modal-tarif").text(
                new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(tarif)
            );

            $("#confirmModal").removeClass("hidden");
        }

        function cancelSelectSlot() {
            selectedSlotId = null;
            selectedSlotData = null;
            $("#confirmModal").addClass("hidden");
        }

        function confirmSelectSlot() {
            if (!selectedSlotId) return;

            let tarifId = selectedSlotData.vehicle_type_id || null;

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
                        setTimeout(loadSlots, 1000); // Refresh setelah 1 detik
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Silakan coba lagi'));
                    $("#confirmModal").addClass("hidden");
                }
            });
        }

        function showTicketModal(responseData, slotData) {
            $("#ticket-slot").text(responseData.slot_code);
            $("#ticket-area").text(slotData.area_name || '-');
            $("#ticket-location").text(slotData.area_location || '-');
            $("#ticket-vehicle").text(slotData.vehicle_type || '-');

            const now = new Date();
            $("#ticket-time").text(
                now.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })
            );

            const tarif = slotData.tarif_rate || 0;
            $("#ticket-price").text(
                new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(tarif)
            );

            try {
                JsBarcode("#barcodeCanvas", responseData.ticket_code || 'TICKET-000', {
                    format: "CODE128",
                    width: 2,
                    height: 60,
                    displayValue: true,
                    fontSize: 12,
                    margin: 5,
                    background: "#ffffff"
                });
            } catch (e) {
                console.error("Barcode error:", e);
            }

            $("#ticketModal").removeClass("hidden");
        }

        function closeTicketModal() {
            $("#ticketModal").addClass("hidden");
            selectedSlotData = null;
        }

        function printTicket() {
            const originalTitle = document.title;
            document.title = 'E-Karcis Parkir';
            window.print();
            document.title = originalTitle;
        }

        // AUTO REFRESH SETIAP 1 DETIK
        setInterval(loadSlots, 1000); // ← UBAH DARI 5000 KE 1000
        $(document).ready(loadSlots);
    </script>

    <style>
        #ticketModal {
            font-family: 'Inter', sans-serif;
        }

        #ticketModal .space-y-3>div {
            padding: 0.4rem 0;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #ticketModal,
            #ticketModal * {
                visibility: visible;
            }

            #ticketModal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border-radius: 0;
                padding: 1.5rem;
            }

            #ticketModal button {
                display: none !important;
            }
        }
    </style>

@endsectionc
