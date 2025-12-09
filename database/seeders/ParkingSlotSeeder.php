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

        // FIXED DISTANCES
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
        // 🔵 AREA A
        // ==========================
        if ($areaAId) {
            foreach ($distanceMap as $num => $distance) {
                $data[] = [
                    'area_id'              => $areaAId,
                    'slot_code'            => "A{$num}",
                    'status'               => 'empty',
                    'distance_from_entry' => $distance,
                    'last_update'         => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        // ==========================
        // 🔴 AREA B
        // ==========================
        if ($areaBId) {
            foreach ($distanceMap as $num => $distance) {
                $data[] = [
                    'area_id'              => $areaBId,
                    'slot_code'            => "B{$num}",
                    'status'               => 'empty',
                    'distance_from_entry' => $distance,
                    'last_update'         => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        // INSERT
        DB::table('parking_slots')->insert($data);
    }
}
