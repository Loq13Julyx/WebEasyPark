<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $table = 'tarifs';

    protected $fillable = [
        'vehicle_type_id',
        'rate',
    ];

    /**
     * Relasi ke VehicleType
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    /**
     * Relasi ke ParkingRecord
     */
    public function parkingRecords()
    {
        return $this->hasMany(ParkingRecord::class, 'tarif_id');
    }
}
