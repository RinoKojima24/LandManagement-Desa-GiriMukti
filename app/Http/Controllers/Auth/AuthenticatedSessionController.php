<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\WaHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */


    public function register() {
        return view('Auth.register');
    }

    public function sendOtp(Request $request)
    {

        if($request->type == "login") {
            $request->validate([
                'email' => 'required',
            ]);

            $user = User::where('email', $request->email)->orWhere('no_telepon', $request->email)->orWhere('nik', $request->email)->first();

            if($user) {
                if($user->is_active == 1) {
                    return response()->json([
                        'status' => 'no',
                        'message' => 'Mohon Maaf Akun Pengguna belum dikonfirmasi oleh Admin!'
                    ]);
                } else {
                    // Generate OTP 4 digit
                    if($user->demo == 1) {
                        $otp = 1111;
                    } else {
                        $otp = rand(1000, 9999);
                    }

                    // Simpan OTP ke session
                    session(['otp' => $otp]);

                    // Kirim email
                    Mail::to($user->email)->send(new OtpMail($otp, $user->nama));

                    $pesan = "Halo $user->nama_petugas, Kode OTP kamu adalah: $otp \nGunakan kode ini untuk verifikasi akun.";
                    WaHelpers::sendWa($user->no_telepon, $pesan);

                    return response()->json([
                        'status' => 'ok',
                        'message' => 'OTP dikirim ke email'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'no',
                    'message' => 'Email Pengguna tidak tersedia!'
                ]);
            }
        }

        if($request->type == "register") {
            $request->validate([
                'nama' => 'required',
                'email' => 'required|email',
                'telepon' => 'required',
                'nik' => 'required'
            ]);

            // Generate OTP 4 digit
            $otp = rand(1000, 9999);

            // Simpan OTP ke session
            session(['otp' => $otp]);

            // Kirim email
            Mail::to($request->email)->send(new OtpMail($otp, $request->nama));
            $pesan = "Halo $request->nama, Kode OTP kamu adalah: $otp \nGunakan kode ini untuk verifikasi akun.";
            WaHelpers::sendWa($request->telepon, $pesan);

            return response()->json([
                'status' => 'ok',
                'message' => 'OTP dikirim ke email'
            ]);
        }
    }


    public function checkOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        if ($request->otp == session('otp')) {

            if($request->type == "register") {

                $gambarPath = null;

                if ($request->hasFile('foto_ktp')) {
                    $gambarFile = $request->file('foto_ktp');
                    $gambarPath = $gambarFile->store('foto_ktp');
                }

                $user = User::create([
                    'email' => $request->email,
                    'nama_petugas' => $request->nama,
                    'no_telepon' => $request->telepon,
                    'nik' => $request->nik,
                    'foto_ktp' => $gambarPath,

                    'role' => 'warga',
                    'is_active' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);



                return response()->json([
                    'status' => 'ok',
                    'message' => 'OTP benar',
                    'test' => $gambarPath,
                ]);

            }

             if($request->type == "login") {
                $user = User::where('email', $request->email)->orWhere('no_telepon', $request->email)->first();
                Auth::login($user, true);

                return response()->json([
                    'status' => 'ok',
                    'message' => 'Login Berhasil',
                ]);
             }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'OTP salah'
        ]);
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
         $user = $request->user();

           if (!$user->is_active) {
        Auth::logout();
        return redirect()->route('login')->withErrors(['email' => 'Your account is inactive. Please contact the administrator.']);
    }
        $user->update([
            'last_login' => now(),
        ]);
        return redirect()->route('home')->with('success', 'Login successful!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
