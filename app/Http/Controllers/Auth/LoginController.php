<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_id' => 'required|string|max:20',
            'password' => 'required|string|min:8'
        ]);

        try {
            if (Auth::attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/dashboard');
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
        return 'user_id';
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
