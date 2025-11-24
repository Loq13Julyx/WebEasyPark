<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Parkir</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .filter-info h3 {
            font-size: 13px;
            margin-bottom: 8px;
            color: #333;
        }

        .filter-info p {
            font-size: 11px;
            margin: 3px 0;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background-color: #333;
            color: #fff;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            font-weight: bold;
            font-size: 11px;
        }

        table td {
            font-size: 11px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            border-radius: 3px;
            font-weight: bold;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-success {
            background-color: #28a745;
            color: #fff;
        }

        .badge-primary {
            background-color: #007bff;
            color: #fff;
        }

        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }

        .summary {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .summary h3 {
            font-size: 13px;
            margin-bottom: 10px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: bold;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 15mm;
            }
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <h1>LAPORAN DATA PARKIR</h1>
        <p>Sistem Manajemen Parkir</p>
        <p>Dicetak pada: {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY - HH:mm') }} WIB</p>
    </div>

    {{-- Filter Information --}}
    @if ($filters['start_date'] || $filters['end_date'] || $filters['status'] || $filters['payment_status'])
        <div class="filter-info">
            <h3>Filter yang Diterapkan:</h3>

            @if ($filters['start_date'] || $filters['end_date'])
                <p>
                    <strong>Periode:</strong>
                    @if ($filters['start_date'] && $filters['end_date'])
                        {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}
                    @elseif($filters['start_date'])
                        Dari {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }}
                    @else
                        Sampai {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}
                    @endif
                </p>
            @endif

            @if ($filters['status'])
                <p>
                    <strong>Status Parkir:</strong>
                    {{ $filters['status'] == 'in' ? 'Sedang Parkir' : 'Telah Keluar' }}
                </p>
            @endif

            @if ($filters['payment_status'])
                <p>
                    <strong>Status Pembayaran:</strong>
                    {{ $filters['payment_status'] == 'paid' ? 'Pembayaran Selesai' : 'Menunggu Pembayaran' }}
                </p>
            @endif
        </div>
    @endif

    {{-- Table Data --}}
    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="12%">Kode Tiket</th>
                <th width="10%">Tarif</th>
                <th width="18%">Waktu Masuk</th>
                <th width="18%">Waktu Keluar</th>
                <th class="text-center" width="15%">Status Parkir</th>
                <th class="text-center" width="15%">Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $record)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $record->ticket_code }}</strong></td>
                    <td>Rp {{ number_format($record->tarif->rate ?? 0, 0, ',', '.') }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($record->entry_time)->format('d/m/Y H:i') }}<br>
                        <small style="color: #666;">{{ $record->gateIn->name ?? '-' }}</small>
                    </td>
                    <td>
                        @if ($record->exit_time)
                            {{ \Carbon\Carbon::parse($record->exit_time)->format('d/m/Y H:i') }}<br>
                            <small style="color: #666;">{{ $record->gateOut->name ?? '-' }}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($record->status == 'in')
                            <span class="badge badge-warning">Sedang Parkir</span>
                        @else
                            <span class="badge badge-success">Telah Keluar</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($record->payment_status == 'paid')
                            <span class="badge badge-primary">Selesai</span>
                        @else
                            <span class="badge badge-danger">Menunggu</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #999;">
                        Tidak ada data parkir yang ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Summary --}}
    @if ($records->isNotEmpty())
        <div class="summary">
            <h3>Ringkasan Data</h3>
            <div class="summary-item">
                <span>Total Data:</span>
                <span>{{ $records->count() }} record</span>
            </div>
            <div class="summary-item">
                <span>Sedang Parkir:</span>
                <span>{{ $records->where('status', 'in')->count() }} kendaraan</span>
            </div>
            <div class="summary-item">
                <span>Telah Keluar:</span>
                <span>{{ $records->where('status', 'out')->count() }} kendaraan</span>
            </div>
            <div class="summary-item">
                <span>Pembayaran Selesai:</span>
                <span>{{ $records->where('payment_status', 'paid')->count() }} transaksi</span>
            </div>
            <div class="summary-item">
                <span>Menunggu Pembayaran:</span>
                <span>{{ $records->where('payment_status', 'unpaid')->count() }} transaksi</span>
            </div>
            <div class="summary-item">
                <span>Total Pendapatan:</span>
                <span>Rp
                    {{ number_format($records->where('payment_status', 'paid')->sum('tarif.rate'), 0, ',', '.') }}</span>
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
    </div>

    {{-- Print Button (Hidden when printing) --}}
    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()"
            style="padding: 10px 30px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
            <span style="margin-right: 5px;">🖨️</span> Cetak Dokumen
        </button>
        <button onclick="window.close()"
            style="padding: 10px 30px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            Tutup
        </button>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>

</html>
