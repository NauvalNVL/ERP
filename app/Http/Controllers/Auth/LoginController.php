<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            // Log untuk debugging
            Log::info('Login attempt', ['email' => $request->email]);

            // Cek apakah user ada dan aktif
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                Log::info('User not found', ['email' => $request->email]);
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Email tidak ditemukan.']);
            }

            if ($user->status === 'inactive') {
                Log::info('Inactive user attempted login', ['email' => $request->email]);
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Akun Anda tidak aktif.']);
            }

            // Coba login
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                Log::info('Login successful', ['user_id' => Auth::id()]);
                
                $request->session()->regenerate();
                
                return redirect()->intended('dashboard');
            }

            Log::info('Login failed - wrong password', ['email' => $request->email]);
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password yang dimasukkan tidak sesuai.']);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Terjadi kesalahan saat login.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
