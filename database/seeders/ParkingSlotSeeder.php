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

        // DATA FIX
        $distanceMap = [
            1 => 42,
            2 => 48,
            3 => 54,
            4 => 60,
            5 => 66,
            6 => 72,
        ];

        $data = [];

        // Generate Area A
        if ($areaAId) {
            foreach ($distanceMap as $num => $distance) {
                $slot = "A{$num}";

                $data[] = [
                    'area_id'             => $areaAId,
                    'slot_code'           => $slot,
                    'status'              => 'empty',
                    'distance_from_entry' => $distance,
                    'route_direction'     => "Lurus, belok kiri, lalu lurus menuju slot {$slot}",
                    'last_update'         => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        // Generate Area B
        if ($areaBId) {
            foreach ($distanceMap as $num => $distance) {
                $slot = "B{$num}";

                $data[] = [
                    'area_id'             => $areaBId,
                    'slot_code'           => $slot,
                    'status'              => 'empty',
                    'distance_from_entry' => $distance,
                    'route_direction'     => "Lurus, belok kiri, lalu lurus menuju slot {$slot}",
                    'last_update'         => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        if (!empty($data)) {
            DB::table('parking_slots')->insert($data);
        }
    }
}
