<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean Online - {{ $antrean->nomor_antrean }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #000;
            color: #333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            max-width: 400px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .header {
            background: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .back-btn {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #333;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }

        .back-btn:hover {
            color: #007bff;
        }

        .header-title {
            font-size: 16px;
            color: #007bff;
            font-weight: 500;
        }

        .content {
            padding: 30px 20px;
        }

        .qr-section {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            border: 2px solid #e0e0e0;
            padding: 10px;
            background: white;
        }

        .qr-code svg {
            width: 100%;
            height: 100%;
        }

        .info-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-row {
            margin-bottom: 15px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .nomor-antrean {
            font-size: 32px;
            font-weight: 700;
            color: #000;
            letter-spacing: 2px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .status-menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .status-dipanggil {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-selesai {
            background: #d4edda;
            color: #155724;
        }

        .status-batal {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #6c757d;
            color: white;
        }

        .btn-primary:hover {
            background: #5a6268;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }

        .estimasi-text {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        @media print {
            body {
                background: white;
            }
            .action-buttons,
            .header {
                display: none;
            }
            .container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('antrean.index') }}" class="back-btn">
                ← <span class="header-title">Antrean Online</span>
            </a>
        </div>

        <div class="content">
            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="qr-code">
                    {!! $qr_code !!}
                </div>
            </div>

            <!-- Info Antrean -->
            <div class="info-section">
                <div class="info-row">
                    <div class="info-label">Antrean Anda:</div>
                    <div class="nomor-antrean">{{ $antrean->nomor_antrean }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Layanan:</div>
                    <div class="info-value">{{ $antrean->layanan->nama_layanan }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Estimasi Giliran:</div>
                    <div class="info-value">±{{ $estimasi_menunggu }} menit</div>
                    @if($sisa_antrean > 0)
                    <div class="estimasi-text">{{ $sisa_antrean }} antrean di depan Anda</div>
                    @else
                    <div class="estimasi-text">Anda adalah yang pertama!</div>
                    @endif
                </div>

                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div>
                        <span class="status-badge status-{{ $antrean->status }}">
                            {{ ucfirst($antrean->status) }}
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Tanggal:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($antrean->tanggal)->format('d F Y') }}</div>
                </div>
            </div>

            <!-- Alert Info -->
            @if($antrean->status === 'menunggu')
            <div class="alert alert-info">
                📱 Mohon menunggu di area tunggu. Anda akan dipanggil sesuai nomor antrean.
            </div>
            @elseif($antrean->status === 'dipanggil')
            <div class="alert alert-info" style="background: #fff3cd; color: #856404; border-left-color: #ffc107;">
                🔔 Nomor antrean Anda sedang dipanggil! Silakan menuju loket.
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="simpanTiket()">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Simpan Tiket
                </button>

                @if($antrean->status === 'menunggu')
                <button class="btn btn-danger" onclick="batalkanAntrean()" id="btnBatal">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                    Batal Antrean
                </button>
                @endif
            </div>
        </div>
    </div>

    <script>
        function simpanTiket() {
            // Simpan sebagai gambar atau print
            window.print();
        }

        function batalkanAntrean() {
            if (confirm('Apakah Anda yakin ingin membatalkan antrean ini?')) {
                const btn = document.getElementById('btnBatal');
                btn.disabled = true;
                btn.innerHTML = 'Membatalkan...';

                fetch("{{ route('antrean.batal', $antrean->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Antrean berhasil dibatalkan');
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal membatalkan antrean');
                        btn.disabled = false;
                        btn.innerHTML = '<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg> Batal Antrean';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                    btn.disabled = false;
                });
            }
        }
    </script>
</body>
</html>
