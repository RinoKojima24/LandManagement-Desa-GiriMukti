<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;

class AntreanController extends Controller
{
    /**
     * Tampilkan daftar antrean
     */
    public function index(Request $request)
    {
        $query = Antrean::with(['user', 'layanan']);

        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->status($request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal')) {
            $query->tanggal($request->tanggal);
        }

        $antrean = $query->latest()->paginate(20);

        return view('antrean.index', compact('antrean'));
    }

    /**
     * Form untuk membuat antrean baru
     */
    public function create()
    {
    $layanan = Layanan::get();

    // Hitung jumlah antrean dengan status 'menunggu' pada tanggal hari ini
    $jumlahMenunggu = Antrean::whereDate('tanggal', Carbon::today())
        ->where('status', 'menunggu')
        ->count();

    return view('antrean.create', compact('layanan', 'jumlahMenunggu'));

    }



    public function store(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
        ]);

        try {
            DB::beginTransaction();

            $layanan = Layanan::findOrFail($request->layanan_id);
            $tanggalHariIni = Carbon::today();

            // Generate nomor antrean
            $nomorAntrean = $this->generateNomorAntrean($request->layanan_id, $tanggalHariIni);

            // Hitung estimasi giliran (asumsi setiap layanan 30 menit)
            $estimasiGiliran = $this->hitungEstimasiGiliran($request->layanan_id, $tanggalHariIni);

            // Buat antrean baru
            $antrean = Antrean::create([
                'user_id' => Auth::id(),
                'layanan_id' => $request->layanan_id,
                'nomor_antrean' => $nomorAntrean,
                'tanggal' => $tanggalHariIni,
                'status' => 'menunggu',
                'keterangan' => "Estimasi giliran: {$estimasiGiliran} menit"
            ]);

            DB::commit();

            // Generate QR Code
            $qrCode = $this->generateQRCode($antrean);

        return redirect()-> route('antrean.show', $antrean->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat antrean: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate nomor antrean berdasarkan layanan dan tanggal
     */
    private function generateNomorAntrean($layananId, $tanggal)
    {
        // Ambil kode layanan dari database
        $layanan = Layanan::find($layananId);

        // Gunakan field 'kode' atau fallback ke 'kode_layanan' jika kode kosong
        $kodeLayanan = $layanan->kode ?? $layanan->kode_layanan ?? 'A';

        // Hitung jumlah antrean hari ini untuk layanan tersebut
        $jumlahAntrean = Antrean::where('layanan_id', $layananId)
            ->whereDate('tanggal', $tanggal)
            ->count();

        // Format nomor: A-001, A-002, dst
        $nomorUrut = str_pad($jumlahAntrean + 1, 3, '0', STR_PAD_LEFT);

        return "{$kodeLayanan}-{$nomorUrut}";
    }

    /**
     * Hitung estimasi waktu giliran dalam menit
     */
    private function hitungEstimasiGiliran($layananId, $tanggal)
    {
        // Hitung jumlah antrean yang masih menunggu
        $antreanMenunggu = Antrean::where('layanan_id', $layananId)
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->count();

        // Asumsi setiap layanan membutuhkan 30 menit
        $waktuPerLayanan = 30;

        return $antreanMenunggu * $waktuPerLayanan;
    }

    /**
     * Generate QR Code untuk antrean
     */
    private function generateQRCode($antrean)
    {
        $data = [
            'id' => $antrean->id,
            'nomor_antrean' => $antrean->nomor_antrean,
            'layanan' => $antrean->layanan->nama ?? '',
            'tanggal' => $antrean->tanggal->format('Y-m-d'),
        ];

        // Generate QR Code dalam format SVG (tidak perlu imagick)
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate(json_encode($data));

        return $qrCode; // Return SVG string langsung
    }

    /**
     * Method untuk membatalkan antrean
     */
    public function batal($id)
    {
        try {
            $antrean = Antrean::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($antrean->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Antrean tidak dapat dibatalkan'
                ], 400);
            }

            $antrean->update([
                'status' => 'batal',
                'keterangan' => 'Dibatalkan oleh user'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Antrean berhasil dibatalkan',
                'data' => $antrean
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan antrean: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk melihat detail antrean
     */
  public function show($id)
{
    try {
        $antrean = Antrean::with('layanan', 'user')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $qr_code = $this->generateQRCode($antrean);

        $sisa_antrean = Antrean::where('layanan_id', $antrean->layanan_id)
            ->whereDate('tanggal', $antrean->tanggal)
            ->where('nomor_antrean', '<', $antrean->nomor_antrean)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->count();

        $estimasi_menunggu = $sisa_antrean * 30;

        // Return view untuk web
        return view('antrean.show', compact('antrean', 'qr_code', 'sisa_antrean', 'estimasi_menunggu'));

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Antrean tidak ditemukan' . $e->getMessage());
    }
}

    /**
     * Method untuk melihat daftar antrean user
     */
    public function myAntrean()
    {
        try {
            $antrean = Antrean::with('layanan')
                ->where('user_id', Auth::id())
                ->whereDate('tanggal', Carbon::today())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $antrean
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data antrean'
            ], 500);
        }
    }

    /**
     * Method untuk simpan tiket (untuk admin/petugas)
     */
    public function simpanTiket($id)
    {
        try {
            $antrean = Antrean::with('layanan')->findOrFail($id);

            // Generate PDF atau simpan ke database
            // Implementasi sesuai kebutuhan

            return response()->json([
                'success' => true,
                'message' => 'Tiket berhasil disimpan',
                'data' => $antrean
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan tiket'
            ], 500);
        }
    }
}
