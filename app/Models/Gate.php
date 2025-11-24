<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'status',
    ];

    /**
     * Relasi ke ParkingRecord sebagai gate masuk
     */
    public function entriesIn()
    {
        return $this->hasMany(ParkingRecord::class, 'gate_in_id');
    }

    /**
     * Relasi ke ParkingRecord sebagai gate keluar
     */
    public function entriesOut()
    {
        return $this->hasMany(ParkingRecord::class, 'gate_out_id');
    }
}