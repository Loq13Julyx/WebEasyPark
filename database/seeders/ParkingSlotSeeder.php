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

        $areaAId = DB::table('parking_areas')
            ->where('name', 'Area A')
            ->value('id');

        $areaBId = DB::table('parking_areas')
            ->where('name', 'Area B')
            ->value('id');

        $data = [];

        if ($areaAId) {
            for ($i = 1; $i <= 5; $i++) {
                $data[] = [
                    'area_id'             => $areaAId,
                    'slot_code'           => 'A' . $i,
                    'status'              => 'empty',
                    'distance_from_entry' => rand(5, 50),
                    'last_update'         => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        if ($areaBId) {
            for ($i = 1; $i <= 5; $i++) {
                $data[] = [
                    'area_id'             => $areaBId,
                    'slot_code'           => 'B' . $i,
                    'status'              => 'empty',
                    'distance_from_entry' => rand(5, 50),
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
