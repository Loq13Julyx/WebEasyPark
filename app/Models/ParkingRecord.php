<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingRecord extends Model
{
    use HasFactory;

    protected $table = 'parking_records';

    protected $fillable = [
        'tarif_id',
        'ticket_code',
        'entry_time',
        'exit_time',
        'payment_status',
        'status',
        'gate_in_id',
        'gate_out_id',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];

    /**
     * Relasi ke tabel Tarif
     */
    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'tarif_id');
    }

    /**
     * Gate tempat kendaraan masuk
     */
    public function gateIn()
    {
        return $this->belongsTo(Gate::class, 'gate_in_id');
    }

    /**
     * Gate tempat kendaraan keluar
     */
    public function gateOut()
    {
        return $this->belongsTo(Gate::class, 'gate_out_id');
    }
}
