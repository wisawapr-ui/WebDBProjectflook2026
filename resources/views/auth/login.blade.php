@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h2>Login</h2>
    
    @if($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <label>Email:</label>
            <input type="email" name="email" required autofocus>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <!-- โค้ดปุ่ม 2 ปุ่มวางข้างกันใส่ตรงนี้ครับ -->
        <div style="display: flex; gap: 10px; margin-top: 15px;">
            <button type="submit">Login</button>
            <a href="{{ route('admin.login') }}" style="text-decoration: none;">
                <button type="button" style="background-color: #333; color: white; border: 1px solid #ff9900;">
                    เข้าสู่ระบบสำหรับ Admin
                </button>
            </a>
        </div>
        <p style="margin-top: 15px;">ยังไม่มีบัญชี? <a href="{{ route('register') }}" style="color: #ff9900;">สมัครสมาชิก</a></p>
    </form>
@endsection