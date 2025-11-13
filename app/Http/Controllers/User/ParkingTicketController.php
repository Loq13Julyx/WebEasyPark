<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingRecord;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ParkingTicketController extends Controller
{
    /**
     * Tampilkan tiket parkir beserta QR code (pakai GD)
     */
    public function show($id)
    {
        $record = ParkingRecord::with(['parkingSlot', 'vehicleType', 'tarif'])->findOrFail($id);

        // Generate QR Code pakai GD
        $qrCode = base64_encode(
            QrCode::format('png')
                ->size(200)
                ->generate($record->ticket_code)
        );

        return view('user.parking_ticket.show', compact('record', 'qrCode'));
    }
}
