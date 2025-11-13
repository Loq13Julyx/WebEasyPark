<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'total_slots',
        'vehicle_type_id', // tambahkan tipe kendaraan
    ];

    /**
     * Relasi ke tabel parking_slots (One-to-Many)
     */
    public function slots()
    {
        return $this->hasMany(ParkingSlot::class, 'area_id'); // foreign key di parking_slots
    }

    /**
     * Relasi ke tabel vehicle_types (Many-to-One)
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }
}
