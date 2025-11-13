<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id',
        'distance_cm',
        'detected',
        'measured_at',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'measured_at' => 'datetime',
        'detected' => 'boolean',
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}
