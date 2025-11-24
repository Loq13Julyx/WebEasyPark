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
        // Rentang bulan : Juni -> bulan sekarang
        $startMonth = Carbon::create(Carbon::now()->year, 6, 1);
        $endMonth   = Carbon::now();

        // Loop setiap bulan
        while ($startMonth->lessThanOrEqualTo($endMonth)) {

            // Buat jumlah record acak per bulan (20–50 transaksi)
            $recordsCount = rand(20, 50);

            for ($i = 0; $i < $recordsCount; $i++) {

                // Entry date random dalam bulan ini
                $entry = Carbon::create(
                    $startMonth->year,
                    $startMonth->month,
                    rand(1, $startMonth->daysInMonth),
                    rand(6, 20),    // jam masuk 06:00–20:00
                    rand(0, 59)
                );

                // Exit (1–5 jam setelah entry)
                $exit = (clone $entry)->addHours(rand(1, 5))->addMinutes(rand(0, 59));

                ParkingRecord::create([
                    'tarif_id'       => 2, // sesuaikan dengan tarif di DB
                    'ticket_code'    => strtoupper(Str::random(8)),
                    'entry_time'     => $entry,
                    'exit_time'      => $exit,
                    'payment_status' => 'paid',
                    'status'         => 'out',
                    'gate_in_id'     => 1, 
                    'gate_out_id'    => 2,
                ]);
            }

            // naik ke bulan berikutnya
            $startMonth->addMonth();
        }
    }
}
