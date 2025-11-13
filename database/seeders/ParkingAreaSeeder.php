<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParkingAreaSeeder extends Seeder
{
    public function run(): void
    {
        // Misal ID VehicleType untuk Mobil = 2
        $vehicleTypeId = 2;

        DB::table('parking_areas')->insert([
            [
                'name' => 'Area A',
                'location' => 'Basement 1, Near Elevator',
                'status' => 'active',
                'vehicle_type_id' => $vehicleTypeId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Area B',
                'location' => 'Ground Floor, Near Entrance',
                'status' => 'active',
                'vehicle_type_id' => $vehicleTypeId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Area C',
                'location' => 'First Floor, Near Stairs',
                'status' => 'active',
                'vehicle_type_id' => $vehicleTypeId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
