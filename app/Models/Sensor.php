<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'slot_id',
        'type',
        'status',
        'threshold_cm',
        'api_key',
        'last_update',
        'last_distance_cm',
        'last_detected',
    ];

    // 🧠 konversi otomatis tipe data
    protected $casts = [
        'last_update'   => 'datetime',
        'last_detected' => 'boolean',
    ];

    /**
     * 🔗 Relasi ke ParkingSlot (Many-to-One)
     */
    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class, 'slot_id');
    }

    /**
     * 🔗 Relasi ke ParkingArea melalui ParkingSlot
     * Sensor → ParkingSlot → ParkingArea
     */
    public function area()
    {
        return $this->hasOneThrough(
            ParkingArea::class,
            ParkingSlot::class,
            'id',       // foreign key di ParkingSlot
            'id',       // foreign key di ParkingArea
            'slot_id',  // local key di Sensor
            'area_id'   // local key di ParkingSlot
        );
    }

    /**
     * 📊 Relasi ke Reading (One-to-Many)
     * Setiap sensor punya banyak log pembacaan
     */
    public function readings()
    {
        return $this->hasMany(Reading::class);
    }
}
