<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // contoh: Motor, Mobil
            $table->timestamps();
        });

        /**
         * 1️⃣ Parking Areas
         */
        Schema::create('parking_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('location', 150)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Tambah tipe kendaraan (opsional)
            $table->foreignId('vehicle_type_id')
                ->nullable()
                ->constrained('vehicle_types')
                ->nullOnDelete()
                ->comment('Tipe kendaraan utama area parkir');

            $table->timestamps();
        });

        /**
         * 2️⃣ Parking Slots
         */
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('parking_areas')->onDelete('cascade');
            $table->string('slot_code', 10);
            $table->enum('status', ['empty', 'occupied', 'inactive'])->default('empty');

            // 🔹 Tambahan: jarak dari gerbang masuk (meter)
            $table->decimal('distance_from_entry', 6, 2)->nullable()->comment('Jarak slot dari gerbang masuk dalam meter');

            $table->timestamp('last_update')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();

            // Unik per area
            $table->unique(['area_id', 'slot_code']);
        });

        /**
         * 3️⃣ Sensors
         */
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // misal: SEN-01
            $table->foreignId('slot_id')->nullable()->constrained('parking_slots')->nullOnDelete();
            $table->enum('type', ['ultrasonic', 'infrared'])->default('ultrasonic');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('last_update')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
        });

        /**
         * 5️⃣ Tarifs (Harga per jam dan batas per hari)
         */
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel jenis kendaraan
            $table->foreignId('vehicle_type_id')
                ->constrained('vehicle_types')
                ->onDelete('cascade');
            $table->decimal('rate', 10, 2)->default(0);
            $table->timestamps();
        });

        /**
         * 6️⃣ Parking Records (Transaksi Parkir)
         */
        Schema::create('parking_records', function (Blueprint $table) {
            $table->id();

            // Relasi utama
            $table->foreignId('parking_slot_id')->nullable()->constrained('parking_slots')->nullOnDelete();
            $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types')->nullOnDelete();
            $table->foreignId('tarif_id')->nullable()->constrained('tarifs')->nullOnDelete();

            // Tiket parkir
            $table->string('ticket_code')->unique(); // contoh: TKT-20251106-001

            // Waktu
            $table->timestamp('entry_time')->nullable();
            $table->timestamp('exit_time')->nullable();

            // Pembayaran
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');

            // Status parkir
            $table->enum('status', ['in', 'out'])->default('in');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_records');
        Schema::dropIfExists('tarifs');
        Schema::dropIfExists('vehicle_types');
        Schema::dropIfExists('sensors');
        Schema::dropIfExists('parking_slots');
        Schema::dropIfExists('parking_areas');
    }
};
