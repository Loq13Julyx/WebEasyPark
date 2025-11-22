<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ParkingSlotSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $areaAId = DB::table('parking_areas')->where('name', 'Area A')->value('id');
        $areaBId = DB::table('parking_areas')->where('name', 'Area B')->value('id');

        // DISTANCE FIX
        $distanceMap = [
            1 => 42,
            2 => 48,
            3 => 54,
            4 => 60,
            5 => 66,
            6 => 72,
        ];

        $data = [];

        // ==========================
        // 🔵 AREA A - belok kanan
        // ==========================
        if ($areaAId) {
            foreach ($distanceMap as $num => $distance) {
                $slot = "A{$num}";

                if ($num == 1) {
                    $direction = "Dari pintu masuk, lanjutkan lurus kemudian belok kanan menuju slot {$slot}.";
                } else {
                    $direction = "Dari pintu masuk, lanjutkan lurus, lewati A1–A" . ($num - 1) . ", lalu belok kanan menuju slot {$slot}.";
                }

                $data[] = [
                    'area_id'             => $areaAId,
                    'slot_code'           => $slot,
                    'status'              => 'empty',
                    'distance_from_entry' => $distance,
                    'route_direction'     => $direction,
                    'last_update'         => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        // ==========================
        // 🔴 AREA B - belok kiri
        // ==========================
        if ($areaBId) {
            foreach ($distanceMap as $num => $distance) {
                $slot = "B{$num}";

                if ($num == 1) {
                    $direction = "Dari pintu masuk, lanjutkan lurus kemudian belok kiri menuju slot {$slot}.";
                } else {
                    $direction = "Dari pintu masuk, lanjutkan lurus, lewati B1–B" . ($num - 1) . ", lalu belok kiri menuju slot {$slot}.";
                }

                $data[] = [
                    'area_id'             => $areaBId,
                    'slot_code'           => $slot,
                    'status'              => 'empty',
                    'distance_from_entry' => $distance,
                    'route_direction'     => $direction,
                    'last_update'         => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        // INSERT
        if (!empty($data)) {
            DB::table('parking_slots')->insert($data);
        }
    }
}
