<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParkingRecord;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ParkingRecordSeeder extends Seeder
{
    public function run()
    {
        // Rentang bulan: Januari tahun kemarin -> bulan sekarang
        $startMonth = Carbon::create(Carbon::now()->year - 1, 1, 1);
        $endMonth   = Carbon::now();

        while ($startMonth->lessThanOrEqualTo($endMonth)) {

            // Jumlah record acak per bulan untuk variasi grafik
            $recordsCount = rand(400, 500);

            for ($i = 0; $i < $recordsCount; $i++) {

                // Hari acak dalam bulan
                $day = rand(1, $startMonth->daysInMonth);

                // Jam masuk acak: pagi 06-09, siang 10-15, sore 16-20 (lebih banyak pagi & sore)
                $hourDistribution = rand(1, 100);
                if ($hourDistribution <= 40) {
                    $hour = rand(6, 9);   // pagi
                } elseif ($hourDistribution <= 80) {
                    $hour = rand(16, 20); // sore
                } else {
                    $hour = rand(10, 15); // siang
                }

                $entry = Carbon::create(
                    $startMonth->year,
                    $startMonth->month,
                    $day,
                    $hour,
                    rand(0, 59)
                );

                // Durasi parkir lebih variatif: 1–6 jam
                $exit = (clone $entry)->addHours(rand(1, 6))->addMinutes(rand(0, 59));

                ParkingRecord::create([
                    'tarif_id'       => 2,
                    'ticket_code'    => strtoupper(Str::random(8)),
                    'entry_time'     => $entry,
                    'exit_time'      => $exit,
                    'payment_status' => 'paid',
                    'status'         => 'out',
                    'gate_in_id'     => 1,
                    'gate_out_id'    => 2,
                ]);
            }

            $startMonth->addMonth();
        }
    }
}
