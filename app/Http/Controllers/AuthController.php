<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->role === 'administrator') {
                return redirect()->intended('/books');
            } else {
                return redirect()->intended('/katalog');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'alamat' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $request->name,
            'nama_lengkap' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'role' => 'peminjam',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan masuk menggunakan Email dan Kata Sandi Anda.');
    }

    public function profile()
    {
        return view('pelanggan.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $user->id,
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'alamat' => $request->alamat,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function showChoosePfp()
    {
        return view('auth.choose-pfp');
    }

    public function updatePfp(Request $request)
    {
        $request->validate([
            'pfp' => 'required|string',
        ]);

        auth()->user()->update([
            'pfp' => $request->pfp
        ]);

        return redirect()->route('katalog.index')->with('success', 'Keren! Foto profil Anda sudah diperbarui.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
