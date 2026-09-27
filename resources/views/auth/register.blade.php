@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <h2>Register</h2>

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div>
            <label>Name:</label>
            <input type="text" name="name" required autofocus>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <button type="submit">Register</button>
        <p style="margin-top: 15px;">มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}" style="color: #ff9900;">เข้าสู่ระบบ</a></p>
    </form>
@endsection