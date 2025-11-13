<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'officer',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
