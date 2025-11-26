<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GateSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gates')->insert([
            [
                'name' => 'Gate Masuk',
                'location' => 'Pintu Masuk Parkiran',
                'status' => 'closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gate Keluar',
                'location' => 'Pintu Keluar Parkiran',
                'status' => 'closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
