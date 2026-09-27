<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
            return redirect()->route('activities.index');
        }

        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // สมัครใหม่เป็น user เสมอ
        ]);

        Auth::login($user);
        return redirect()->route('activities.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            // เช็คว่าคนที่ล็อกอินเข้ามามีสิทธิ์เป็น admin หรือไม่
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('activities.index');
            } else {
                // ถ้าล็อกอินถูกแต่ไม่ใช่แอดมิน ให้เตะออก
                Auth::logout();
                return back()->withErrors([
                    'email' => 'บัญชีนี้ไม่มีสิทธิ์เข้าถึงระบบ Admin',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านแอดมินไม่ถูกต้อง',
        ]);
    }
}
