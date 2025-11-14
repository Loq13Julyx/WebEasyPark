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
                'vehicle_type_id' => 1, 
                'rate'    => 2000,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'vehicle_type_id' => 2,
                'rate'    => 5000,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ]);
    }
}
