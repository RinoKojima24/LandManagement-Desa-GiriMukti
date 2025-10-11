<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrean - {{ $antrean->nomor_antrean }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        .tiket {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border: 2px dashed #333;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .header p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .nomor-antrean {
            text-align: center;
            margin: 30px 0;
        }

        .nomor-antrean h2 {
            font-size: 36px;
            color: #3498db;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .barcode {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
            margin: 20px 0;
        }

        .barcode img {
            max-width: 100%;
            height: auto;
        }

        .info {
            margin: 20px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }

        .info-value {
            color: #555;
        }

        .status {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .status.menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #333;
            font-size: 12px;
            color: #7f8c8d;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .tiket {
                border: 2px solid #333;
                box-shadow: none;
            }

            @page {
                size: A5;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="tiket">
        <div class="header">
            <h1>TIKET ANTREAN</h1>
            <p>{{ config('app.name', 'Sistem Antrean') }}</p>
        </div>

        <div class="nomor-antrean">
            <h2>{{ $antrean->nomor_antrean }}</h2>
        </div>

        <div class="barcode">
            <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode">
        </div>

        <div class="info">
            <div class="info-row">
                <span class="info-label">Nama:</span>
                <span class="info-value">{{ $antrean->user->nama_petugas }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Layanan:</span>
                <span class="info-value">{{ $antrean->layanan->nama_layanan }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal:</span>
                <span class="info-value">{{ $antrean->tanggal->format('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Waktu:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($antrean->waktu)->format('H:i') }} WIB</span>
            </div>
        </div>

        <div class="status {{ $antrean->status }}">
            Status: {{ strtoupper($antrean->status) }}
        </div>

        <div class="footer">
            <p>Simpan tiket ini dengan baik</p>
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <script>
        // Auto print saat halaman dimuat
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
