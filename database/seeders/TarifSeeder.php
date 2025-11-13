<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TarifSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('tarifs')->insert([
            [
                'vehicle_type_id' => 1, // Motor
                'rate'    => 2000,  // tarif awal
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'vehicle_type_id' => 2, // Mobil
                'rate'    => 5000,  // tarif awal
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ]);
    }
}
