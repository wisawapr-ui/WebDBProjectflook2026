<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Movie Review Portal')</title>
    
    <!-- เรียกไฟล์ CSS หลักของคุณ -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- แถบ Header / Navbar ด้านบน -->
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background-color: #0b1329; border-bottom: 1px solid #1e293b;">
        
        <!-- ฝั่งซ้าย: ชื่อเว็บไซต์ -->
        <div>
 <a class="nameweb" href="{{ route('activities.index') }}" style="font-size: 20px; font-weight: bold; color: #8b5cf6; text-decoration: none; display: flex; align-items: center; gap: 8px;">
    🎬 Movie Review Portal
</a>
        </div>

        <!-- ฝั่งขวา: ข้อมูลผู้ใช้ + ปุ่มโปรไฟล์ + ปุ่ม Logout -->
        <div style="display: flex; align-items: center; gap: 12px;">
            @auth
                <span style="color: #ff9900; font-size: 14px;">
                    ผู้ใช้งาน: <strong>{{ Auth::user()->name }}</strong> (Role: {{ Auth::user()->role }})
                </span>

                <a href="{{ route('profile.index') }}" style="color: #ff9900; text-decoration: none; font-weight: bold; font-size: 14px; background: #1e293b; padding: 8px 14px; border-radius: 6px; border: 1px solid #334155; display: inline-flex; align-items: center; gap: 6px;">
                    👤 โปรไฟล์ของฉัน
                </a>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background-color: #ff9900; color: #000; border: none; padding: 8px 18px; font-weight: bold; font-size: 14px; border-radius: 6px; cursor: pointer;">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color: #ff9900; text-decoration: none; font-weight: bold; margin-right: 10px;">Login</a>
                <a href="{{ route('register') }}" style="color: #fff; text-decoration: none; font-weight: bold;">Register</a>
            @endauth
        </div>

    </header>

    <!-- เนื้อหาแต่ละหน้า -->
    <main class="container" style="padding: 20px;">
        @yield('content')
    </main>

</body>
</html>