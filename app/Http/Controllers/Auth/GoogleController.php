<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    /**
     * Redirect ke halaman login Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Cari user berdasarkan google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // Update avatar jika ada perubahan
                if ($user->avatar !== $googleUser->avatar) {
                    $user->update(['avatar' => $googleUser->avatar]);
                }
            } else {
                // Cek apakah email sudah terdaftar
                $user = User::where('email', $googleUser->email)->first();
                
                if ($user) {
                    // Update user yang sudah ada dengan google_id
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                } else {
                    // Buat user baru
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => Hash::make(uniqid()), // Random password
                    ]);
                }
            }

            // Generate OTP
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = now()->addMinutes(5); // OTP berlaku 5 menit
            
            // Simpan OTP ke database
            $user->update([
                'otp_code' => $otpCode,
                'otp_expires_at' => $expiresAt,
            ]);

            // Kirim OTP via email
            Mail::raw("Kode OTP Anda untuk login adalah: $otpCode\n\nKode ini berlaku selama 5 menit.\n\nJika Anda tidak melakukan permintaan ini, abaikan email ini.", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode OTP Login - Workshop Framework');
            });

            // Simpan user ID di session untuk verifikasi OTP
            session(['otp_user_id' => $user->id]);

            // Redirect ke halaman input OTP
            return redirect()->route('otp.verify.form')->with('success', 'Kode OTP telah dikirim ke email Anda.');
            
        } catch (Exception $e) {
            // Log error untuk debugging
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Login dengan Google gagal: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form input OTP
     */
    public function showOTPForm()
    {
        if (!session('otp_user_id')) {
            return redirect('/login')->with('error', 'Session expired. Silakan login lagi.');
        }
        
        return view('auth.verify-otp');
    }

    /**
     * Verifikasi OTP
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Session expired. Silakan login lagi.');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect('/login')->with('error', 'User tidak ditemukan.');
        }

        // Cek apakah OTP sudah expired
        if ($user->otp_expires_at < now()) {
            return back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan login lagi.');
        }

        // Cek apakah OTP cocok
        if ($user->otp_code !== $request->otp) {
            return back()->with('error', 'Kode OTP tidak valid.');
        }

        // OTP valid, clear OTP dan login user
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Hapus session OTP
        session()->forget('otp_user_id');

        // Login user
        Auth::login($user);

        return redirect()->intended('/home')->with('success', 'Login berhasil!');
    }
}
