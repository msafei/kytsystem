<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil user berdasarkan username
        $user = User::where('username', $credentials['username'])->first();

        // Cek apakah user ditemukan dan password benar
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->with('error', 'Username atau password salah.');
        }

        // Jika valid, login user
        Auth::login($user, $request->has('remember'));

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logout berhasil.');
    }
}
