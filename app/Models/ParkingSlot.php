<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',             
        'slot_code',
        'status',
        'distance_from_entry', 
        'last_update',
    ];

    /**
     * Relasi ke tabel parking_areas (Many-to-One)
     */
    public function area()
    {
        return $this->belongsTo(ParkingArea::class, 'area_id');
    }

    /**
     * Relasi ke tabel parking_records (One-to-Many)
     */
    public function parkingRecords()
    {
        return $this->hasMany(ParkingRecord::class, 'parking_slot_id');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }
}
