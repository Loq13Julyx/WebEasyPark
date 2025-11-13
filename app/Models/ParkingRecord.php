<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingRecord extends Model
{
    use HasFactory;

    protected $table = 'parking_records';

    protected $fillable = [
        'parking_slot_id',
        'vehicle_type_id',
        'tarif_id',
        'ticket_code',
        'entry_time',
        'exit_time',
        'payment_status',
        'status',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];

    /**
     * Relasi ke tabel ParkingSlot
     */
    public function parkingSlot()
    {
        return $this->belongsTo(ParkingSlot::class, 'parking_slot_id');
    }

    /**
     * Relasi ke tabel VehicleType
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    /**
     * Relasi ke tabel Tarif
     */
    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'tarif_id');
    }
}
