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
        'parking_slot_id',
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
     * Relasi: ParkingRecord dimiliki oleh Tarif
     */
    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'tarif_id');
    }

    /**
     * Relasi: ParkingRecord dimiliki oleh Slot Parkir
     */
    public function parkingSlot()
    {
        return $this->belongsTo(ParkingSlot::class, 'parking_slot_id');
    }

    /**
     * Gate tempat kendaraan MASUK
     */
    public function gateIn()
    {
        return $this->belongsTo(Gate::class, 'gate_in_id');
    }

    /**
     * Gate tempat kendaraan KELUAR
     */
    public function gateOut()
    {
        return $this->belongsTo(Gate::class, 'gate_out_id');
    }
}
