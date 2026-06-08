<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Session::has('user')) {
            return redirect('/');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3',
            'password' => 'required|min:6',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.min' => 'Username minimal 3 karakter',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Simple authentication (untuk demo, tidak menggunakan hash)
        // Dalam production, gunakan Auth::attempt() dengan hashing
        $validUsers = [
            ['username' => 'admin', 'password' => 'admin123'],
            ['username' => 'user', 'password' => 'user123'],
        ];

        $user = null;
        foreach ($validUsers as $validUser) {
            if ($request->username === $validUser['username'] && 
                $request->password === $validUser['password']) {
                $user = $validUser;
                break;
            }
        }

        if ($user) {
            Session::put('user', [
                'username' => $user['username'],
                'login_time' => now(),
            ]);
            return redirect('/')->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        Session::forget('user');
        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
