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

        // Ambil semua area (misal: ['Area A' => 1, 'Area B' => 2, ...])
        $areas = DB::table('parking_areas')->pluck('id', 'name');

        $data = [];

        foreach (['A', 'B', 'C', 'D'] as $letter) {
            $areaName = 'Area ' . $letter;
            $areaId = $areas[$areaName] ?? null;

            if (!$areaId) continue; // Skip kalau area belum ada

            for ($i = 1; $i <= 3; $i++) {
                $data[] = [
                    'area_id'             => $areaId,
                    'slot_code'           => $letter . $i,
                    'status'              => 'empty',
                    'distance_from_entry' => rand(5, 50), // contoh jarak acak antara 5–50 meter
                    'last_update'         => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        DB::table('parking_slots')->insert($data);
    }
}
