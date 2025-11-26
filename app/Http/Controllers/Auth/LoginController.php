<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserCps;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Auth/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string|max:20',
            'password' => 'required|string|min:8'
        ]);

        $credentials = [
            'userID' => $request->user_id,
            'password' => $request->password
        ];

        try {
            if (Auth::attempt($credentials, $request->remember)) {
                $user = Auth::user();

                // Cegah login jika status user tidak aktif/obsolete
                if ($user && $user->status !== 'A') {
                    Auth::logout();

                    return back()->withErrors([
                        'user_id' => 'User tidak aktif/obsolete dan tidak dapat login. Silakan hubungi administrator.',
                    ]);
                }

                // Regenerate session ID for security (CSRF token tetap agar meta csrf di SPA tetap sinkron)
                $request->session()->regenerate();

                // Update login info di tabel usercps (hanya untuk UserCps)
                if ($user instanceof UserCps) {
                    $user->updateLoginInfo();
                    Log::info('Login info updated for user: ' . $user->userID);
                }

                return redirect('/dashboard');
            }
        } catch (\Exception $e) {
            Log::error('Login error: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem');
        }

        return back()->withErrors([
            'user_id' => 'Kredensial tidak valid',
        ]);
    }

    public function username()
    {
        return 'userID';
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
