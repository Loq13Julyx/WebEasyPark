<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ParkingRecordSeeder extends Seeder
{
    public function run()
    {
        // Mulai dari 1 Januari tahun lalu sampai KEMARIN (bukan hari ini)
        $startDate = Carbon::create(Carbon::now()->year - 1, 1, 1)->startOfDay();
        $endDate   = Carbon::yesterday()->endOfDay(); // ✅ dibatasi sampai kemarin

        $slotIds = ParkingSlot::pluck('id')->toArray();

        if (count($slotIds) === 0) {
            $this->command->error('Seeder gagal: parking_slots belum ada data.');
            return;
        }

        while ($startDate->lessThanOrEqualTo($endDate)) {

            // Jumlah kendaraan per HARI
            $recordsPerDay = rand(10, 25);

            for ($i = 0; $i < $recordsPerDay; $i++) {

                // Distribusi jam masuk
                $hourDistribution = rand(1, 100);
                if ($hourDistribution <= 40) {
                    $hour = rand(6, 9);    // pagi
                } elseif ($hourDistribution <= 80) {
                    $hour = rand(16, 20); // sore
                } else {
                    $hour = rand(10, 15); // siang
                }

                $entry = Carbon::create(
                    $startDate->year,
                    $startDate->month,
                    $startDate->day,
                    $hour,
                    rand(0, 59)
                );

                $exit = (clone $entry)
                    ->addHours(rand(1, 6))
                    ->addMinutes(rand(0, 59));

                ParkingRecord::create([
                    'tarif_id'        => 2,
                    'parking_slot_id' => $slotIds[array_rand($slotIds)],
                    'ticket_code'     => strtoupper(Str::random(8)),
                    'entry_time'      => $entry,
                    'exit_time'       => $exit,
                    'payment_status'  => 'paid',
                    'status'          => 'out',
                ]);
            }

            // Pindah ke hari berikutnya
            $startDate->addDay();
        }
    }
}
