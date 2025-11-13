<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class YoloController extends Controller
{
    public function DeteksiYolo(Request $request)
    {
        // Validasi data dari Python
        $validated = $request->validate([
            'label'      => 'required|string',
            'confidence' => 'required|numeric',
            'timestamp'  => 'required|date_format:Y-m-d H:i:s',
        ]);

        // Di sini kamu bisa:
        // - Simpan ke database
        // - Tulis ke log
        // - Trigger proses lain
        // Untuk sekarang kita cuma balikin respons aja

        return response()->json([
            'success'   => true,
            'message'   => 'Data YOLO diterima 🚀',
            'received'  => $validated,
        ], 200);
    }

    public function SensorUltrasonik()
    {
        
        return response()->json([
            'success'   => true,
            'message'   => 'Data YOLO diterima 🚀',
        ], 200);
    }
}
