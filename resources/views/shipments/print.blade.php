<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $shipment->shipment_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 12px; }

        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 120px; }

        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .content-table th, .content-table td { border: 1px solid black; padding: 8px; text-align: left; }
        .content-table th { background-color: #f0f0f0; text-align: center; }

        .footer { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { margin-top: 60px; border-bottom: 1px solid black; }

        @media print {
            @page { margin: 10mm; }
            body { padding: 0; }
            button { display: none; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" style="position: fixed; top: 10px; right: 10px; padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 5px;">
        Cetak Dokumen
    </button>

    <div class="header">
        <h1>Surat Jalan Pengiriman</h1>
        <p>SPPG Super App - Program Makan Bergizi</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">No. Surat Jalan</td>
            <td>: {{ $shipment->shipment_number }}</td>
            <td class="label">Tanggal</td>
            <td>: {{ $shipment->departure_time->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Driver</td>
            <td>: {{ $shipment->driver->name }}</td>
            <td class="label">Waktu Berangkat</td>
            <td>: {{ $shipment->departure_time->format('H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">Kendaraan</td>
            <td>: -</td>
            <td class="label">Status</td>
            <td>: {{ $shipment->status }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 40%;">Tujuan Sekolah</th>
                <th style="width: 15%;">Jumlah (Porsi)</th>
                <th style="width: 20%;">Jam Terima</th>
                <th style="width: 20%;">Tanda Tangan Penerima</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipment->destinations as $index => $dest)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $dest->school->name }}</strong><br>
                        <span style="font-size: 11px;">{{ $dest->school->address }}</span>
                    </td>
                    <td style="text-align: center;">{{ $dest->quantity }}</td>
                    <td></td> <td></td> </tr>
            @endforeach
            <tr>
                <td colspan="2" style="text-align: right; font-weight: bold;">Total Muatan</td>
                <td style="text-align: center; font-weight: bold;">{{ $shipment->destinations->sum('quantity') }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Disiapkan Oleh (Gudang)</p>
            <div class="signature-line"></div>
        </div>
        <div class="signature-box">
            <p>Driver / Pengirim</p>
            <div class="signature-line"></div>
            <p>{{ $shipment->driver->name }}</p>
        </div>
        <div class="signature-box">
            <p>Mengetahui (Admin)</p>
            <div class="signature-line"></div>
        </div>
    </div>

</body>
</html>
