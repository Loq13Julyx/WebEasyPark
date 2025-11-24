@extends('layouts.app')

@section('title', 'Rekomendasi Slot Parkir')

@section('content')
    <div class="min-h-screen bg-slate-100 py-6 px-4">
        <div class="max-w-7xl mx-auto">

            {{-- HEADER SELAMAT DATANG --}}
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-8 rounded-2xl shadow-lg text-white">
                    <h1 class="text-3xl font-extrabold mb-2">Selamat Datang 👋</h1>

                    <p class="text-blue-100 text-md leading-relaxed">
                        Temukan slot parkir yang <span class="font-semibold">kosong</span> dan
                        paling <span class="font-semibold">dekat</span> dengan pintu masuk secara cepat & otomatis.
                    </p>

                    {{-- Rekomendasi --}}
                    <div class="mt-6">
                        <p class="text-xs text-blue-100 uppercase tracking-wider font-medium mb-3">
                            Rekomendasi Terbaik
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="recommended-container">
                            {{-- AJAX OUTPUT --}}
                        </div>
                    </div>
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
@endsection

@section('scripts')
    <script>
        function loadSlots() {
            $.ajax({
                url: "{{ route('user.recommendations.loadData') }}",
                method: "POST",
                data: {
                    area_id: $("#area_id").val(),
                    vehicle_type_id: $("#vehicle_type_id").val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    updateRecommendationUI(res.recommended);
                    updateSlotMapUI(res.slots);
                }
            });
        }

        // ========================
        // Rekomendasi
        // ========================
        function updateRecommendationUI(data) {
            $("#recommended-container").empty();

            if (data.length === 0) {
                $("#recommended-container").html(`
                <div class="col-span-3 flex items-center gap-4 bg-white/10 p-4 rounded-xl">
                    <div class="bg-gradient-to-br from-yellow-400/30 to-orange-400/30 p-4 rounded-xl">
                        <span class="text-3xl">⚠️</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-blue-100 uppercase font-medium mb-1">Rekomendasi</p>
                        <p class="text-sm text-yellow-100">Tidak ada slot yang cocok</p>
                    </div>
                </div>
            `);
                return;
            }

            data.slice(0, 3).forEach((slot, index) => {
                $("#recommended-container").append(`
                <div class="flex flex-col gap-3 bg-white/15 backdrop-blur-xl p-4 rounded-xl border border-white/20">

                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-emerald-400/30 to-teal-400/30 p-4 rounded-xl">
                            <span class="text-3xl">⭐</span>
                        </div>

                        <div class="flex-1">
                            <p class="text-xs text-blue-100 uppercase font-semibold mb-1">
                                Rekomendasi ${index + 1}
                            </p>
                            <p class="text-xl font-bold text-white">${slot.slot_code}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        <span class="text-sm text-blue-200">📏</span>
                        <p class="text-xs text-blue-200">${slot.distance_from_entry}m dari pintu masuk</p>
                    </div>

                    <div class="flex items-start gap-1">
                        <span class="text-sm text-blue-200">➡️</span>
                        <p class="text-xs text-blue-200">
                            ${slot.route_direction ?? 'Rute belum tersedia'}
                        </p>
                    </div>

                </div>
            `);
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

                $(`#area-${slot.area_id}`).append(`
                <div class="relative h-64 w-40 min-w-[160px] rounded-2xl shadow-lg border-2 
                    transition-all duration-300 hover:scale-105 hover:shadow-xl flex flex-col justify-end items-center 
                    overflow-hidden p-4"
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

        // Auto load setiap 5 detik
        setInterval(loadSlots, 5000);

        $(document).ready(function() {
            loadSlots();
        });
    </script>
@endsection
