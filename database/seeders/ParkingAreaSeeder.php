<?php  

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParkingAreaSeeder extends Seeder
{
    public function run(): void
    {
        $vehicleTypeId = DB::table('vehicle_types')
            ->where('name', 'Mobil')
            ->value('id');

        if (!$vehicleTypeId) {
            return;
        }

        DB::table('parking_areas')->updateOrInsert(
            ['name' => 'Area A'],
            [
                'location'        => 'Outdoor North Zone',
                'status'          => 'active',
                'vehicle_type_id' => $vehicleTypeId,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );

        DB::table('parking_areas')->updateOrInsert(
            ['name' => 'Area B'],
            [
                'location'        => 'Outdoor South Zone',
                'status'          => 'active',
                'vehicle_type_id' => $vehicleTypeId,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );
    }
}
