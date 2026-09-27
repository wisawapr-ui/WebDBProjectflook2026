@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
    <h2 style="color: #ff5252;">Admin Login (ผู้ดูแลระบบ)</h2>
    
    @if($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <div>
            <label style="color: #ff5252;">Admin Email:</label>
            <input type="email" name="email" required autofocus>
        </div>
        <div>
            <label style="color: #ff5252;">Password:</label>
            <input type="password" name="password" required>
        </div>
        <!-- ปุ่มแอดมินใช้สีแดงให้ดูแตกต่าง -->
        <button type="submit" style="background-color: #ff5252; color: white;">Login as Admin</button>
        
        <p style="margin-top: 15px;">
            <a href="{{ route('login') }}" style="color: #888;">กลับไปหน้าเข้าสู่ระบบทั่วไป</a>
        </p>
    </form>
@endsection