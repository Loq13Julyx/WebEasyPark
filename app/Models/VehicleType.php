<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $table = 'vehicle_types';

    protected $fillable = [
        'name',
    ];

    /**
     * Relasi ke Tarif
     */
    public function tarifs()
    {
        return $this->hasMany(Tarif::class, 'vehicle_type_id');
    }

    /**
     * Relasi ke ParkingRecord
     */
    public function parkingRecords()
    {
        return $this->hasMany(ParkingRecord::class, 'vehicle_type_id');
    }
}
