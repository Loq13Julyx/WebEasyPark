<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->timestamps();
        });

        Schema::create('parking_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('location', 150)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('vehicle_type_id')
                ->nullable()
                ->constrained('vehicle_types')
                ->nullOnDelete()
                ->comment('Tipe kendaraan utama area parkir');
            $table->timestamps();
        });

        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('area_id')
                ->constrained('parking_areas')
                ->onDelete('cascade');

            $table->string('slot_code', 10);

            $table->enum('status', ['empty', 'occupied'])
                ->default('empty');

            $table->decimal('distance_from_entry', 6, 2)
                ->nullable()
                ->comment('Jarak slot dari gerbang masuk dalam meter');

            $table->string('route_direction', 255)
                ->nullable()
                ->comment('Rute menuju slot spesifik');

            $table->timestamp('last_update')
                ->useCurrent()
                ->useCurrentOnUpdate();

            $table->timestamps();

            $table->unique(['area_id', 'slot_code']);
        });


        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_type_id')
                ->constrained('vehicle_types')
                ->onDelete('cascade');
            $table->decimal('rate', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('parking_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarif_id')->nullable()->constrained('tarifs')->nullOnDelete();
            $table->string('ticket_code')->unique();
            $table->timestamp('entry_time')->nullable();
            $table->timestamp('exit_time')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('status', ['in', 'out'])->default('in');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_records');
        Schema::dropIfExists('tarifs');
        Schema::dropIfExists('vehicle_types');
        Schema::dropIfExists('parking_slots');
        Schema::dropIfExists('parking_areas');
    }
};
