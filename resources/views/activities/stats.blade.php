@extends('layouts.app')

@section('title', 'แผนภูมิสถิติหนัง')

@section('content')
<!-- โหลด Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div style="max-width: 1050px; margin: 0 auto; color: #fff; font-family: system-ui, -apple-system, sans-serif; padding-bottom: 50px;">

    <!-- ปุ่มย้อนกลับ -->
    <div style="margin-bottom: 25px;">
        <a href="{{ route('activities.index') }}" style="color: #ff9900; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 153, 0, 0.1); padding: 8px 16px; border-radius: 20px; border: 1px solid rgba(255, 153, 0, 0.2);">
            ← กลับไปหน้ารายการหนัง
        </a>
    </div>

    <!-- ส่วนหัว -->
    <div style="text-align: center; margin-bottom: 35px;">
        <h1 style="color: #ff9900; font-size: 28px; font-weight: 800; margin-bottom: 8px;">
            📊 แผนภูมิวิเคราะห์สถิติหนัง & หมวดหมู่
        </h1>
        <p style="color: #94a3b8; font-size: 14px; margin: 0;">
            เปรียบเทียบคะแนนเฉลี่ยเพื่อค้นหาหมวดหมู่ยอดฮิตและเรื่องที่ไม่ควรพลาด
        </p>
    </div>

    <!-- CHART 1: แผนภูมิแท่งคะแนนเฉลี่ยตามหมวดหมู่ -->
    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 25px; margin-bottom: 35px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);">
        <h2 style="color: #ffffff; font-size: 18px; font-weight: 700; margin-top: 0; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            📁 คะแนนเฉลี่ยจำแนกตามหมวดหมู่ (Category Rating)
        </h2>
        <div style="position: relative; height: 320px; width: 100%;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <!-- CHART 2 & 3: แผนภูมิแท่ง หนังดีที่สุด vs หนังคะแนนต่ำสุด -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 25px;">
        
        <!-- แผนภูมิแท่ง หนังดีที่สุด Top 5 -->
        <div style="background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 22px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);">
            <h3 style="color: #10b981; font-size: 16px; font-weight: 700; margin-top: 0; margin-bottom: 15px;">
                🏆 Top 5 หนังคะแนนสูงสุด (Best Rated)
            </h3>
            <div style="position: relative; height: 260px;">
                <canvas id="topMoviesChart"></canvas>
            </div>
        </div>

        <!-- แผนภูมิแท่ง หนังเรตติ้งต่ำสุด Top 5 -->
        <div style="background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 22px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);">
            <h3 style="color: #ef4444; font-size: 16px; font-weight: 700; margin-top: 0; margin-bottom: 15px;">
                ⚠️ Top 5 หนังคะแนนต่ำสุด (Lowest Rated)
            </h3>
            <div style="position: relative; height: 260px;">
                <canvas id="lowestMoviesChart"></canvas>
            </div>
        </div>

    </div>

</div>

<!-- SCRIPT วาดแผนภูมิด้วย CHART.JS -->
<script>
    // ตั้งค่าตัวเลือกส่วนกลางสำหรับ Dark Theme
    const darkThemeOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                titleColor: '#ff9900',
                bodyColor: '#fff',
                borderColor: '#334155',
                borderWidth: 1
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 10,
                grid: { color: 'rgba(255, 255, 255, 0.08)' },
                ticks: { color: '#94a3b8' }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#cbd5e1', font: { weight: 'bold' } }
            }
        }
    };

    // 1. วาด Chart หมวดหมู่หนัง
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($categoryData->pluck('category')) !!},
            datasets: [{
                label: 'คะแนนเฉลี่ย',
                data: {!! json_encode($categoryData->pluck('avg_rating')) !!},
                backgroundColor: 'rgba(255, 153, 0, 0.75)',
                borderColor: '#ff9900',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: darkThemeOptions
    });

    // 2. วาด Chart หนังคะแนนสูงสุด
    const topCtx = document.getElementById('topMoviesChart').getContext('2d');
    new Chart(topCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topMovies->pluck('name')) !!},
            datasets: [{
                label: 'คะแนนเฉลี่ย',
                data: {!! json_encode($topMovies->pluck('reviews_avg_rating')->map(fn($v) => round($v, 1))) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.75)',
                borderColor: '#10b981',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: darkThemeOptions
    });

    // 3. วาด Chart หนังคะแนนต่ำสุด
    const lowestCtx = document.getElementById('lowestMoviesChart').getContext('2d');
    new Chart(lowestCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($lowestMovies->pluck('name')) !!},
            datasets: [{
                label: 'คะแนนเฉลี่ย',
                data: {!! json_encode($lowestMovies->pluck('reviews_avg_rating')->map(fn($v) => round($v, 1))) !!},
                backgroundColor: 'rgba(239, 68, 68, 0.75)',
                borderColor: '#ef4444',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: darkThemeOptions
    });
</script>
@endsection