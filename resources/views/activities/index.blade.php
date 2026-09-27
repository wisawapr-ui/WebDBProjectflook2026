@extends('layouts.app')

@section('title', 'ค้นหาและจัดอันดับหนัง')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; color: #fff; font-family: system-ui, -apple-system, sans-serif;">

    <!-- 1. ส่วนหัวและปุ่มลิงก์ไปหน้าสถิติแผนภูมิแท่ง -->
    <div style="margin-bottom: 30px; text-align: center;">
        <h1 style="color: #ff9900; font-size: 28px; font-weight: 800; margin-bottom: 10px;">
            🎬 ค้นหาหนังยอดเยี่ยม & จัดอันดับความนิยม
        </h1>
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 20px;">
            เช็กสถิติรีวิวจากผู้ใช้จริง ดูว่าเรื่องไหนน่าดู หรือเรื่องไหนควรเลี่ยง!
        </p>

        <!-- 📌 ปุ่มลิงก์ไปหน้าสถิติแผนภูมิแท่ง (Chart Stats) -->
        <a href="{{ route('activities.stats') }}" 
           style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 153, 0, 0.15); color: #ff9900; border: 1px solid #ff9900; padding: 10px 20px; border-radius: 20px; font-weight: 700; text-decoration: none; transition: 0.2s;"
           onmouseover="this.style.background='#ff9900'; this.style.color='#0f172a';" 
           onmouseout="this.style.background='rgba(255, 153, 0, 0.15)'; this.style.color='#ff9900';">
            📊 ดูแผนภูมิสถิติหนัง & หมวดหมู่
        </a>
    </div>

    <!-- 2. แถบตัวกรองสถิติ (Search & Stat Filters) -->
    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 20px; margin-bottom: 30px;">
        <form method="GET" action="{{ route('activities.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; justify-content: space-between;">
            
            <!-- ช่องค้นหาชื่อหนัง -->
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 พิมพ์ชื่อหนังที่ต้องการหา..." 
                    style="width: 100%; padding: 10px 15px; background: #0f172a; border: 1px solid #334155; border-radius: 8px; color: #fff; font-size: 14px;">
            </div>

            <!-- ตัวเลือกหมวดหมู่ -->
            <div style="min-width: 160px;">
                <select name="category" onchange="this.form.submit()" style="width: 100%; padding: 10px 15px; background: #0f172a; border: 1px solid #334155; border-radius: 8px; color: #fff; font-size: 14px;">
                    <option value="">📁 ทุกหมวดหมู่</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- ปุ่มจัดเรียงตามสถิติ (ดี / ไม่ดี) -->
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="{{ route('activities.index', array_merge(request()->query(), ['sort' => 'top_rated'])) }}" 
                   style="padding: 10px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700; transition: 0.2s;
                          background: {{ request('sort') == 'top_rated' ? '#ff9900' : '#0f172a' }}; 
                          color: {{ request('sort') == 'top_rated' ? '#0f172a' : '#ff9900' }}; 
                          border: 1px solid #ff9900;">
                    🏆 หนังยอดเยี่ยม (คะแนนสูง)
                </a>

                <a href="{{ route('activities.index', array_merge(request()->query(), ['sort' => 'lowest_rated'])) }}" 
                   style="padding: 10px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700; transition: 0.2s;
                          background: {{ request('sort') == 'lowest_rated' ? '#ef4444' : '#0f172a' }}; 
                          color: {{ request('sort') == 'lowest_rated' ? '#fff' : '#ef4444' }}; 
                          border: 1px solid #ef4444;">
                    ⚠️ เรตติ้งต่ำ (ควรเลี่ยง)
                </a>

                <a href="{{ route('activities.index', array_merge(request()->query(), ['sort' => 'most_reviewed'])) }}" 
                   style="padding: 10px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700; transition: 0.2s;
                          background: {{ request('sort') == 'most_reviewed' ? '#3b82f6' : '#0f172a' }}; 
                          color: {{ request('sort') == 'most_reviewed' ? '#fff' : '#3b82f6' }}; 
                          border: 1px solid #3b82f6;">
                    🔥 มีคนรีวิวเยอะสุด
                </a>
            </div>

        </form>
    </div>

    <!-- 3. แสดงผลการ์ดรายการหนังพร้อมสถิติคะแนน -->
    @if($activities->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
            @foreach($activities as $activity)
                @php
                    $avgRating = round($activity->reviews_avg_rating ?? 0, 1);
                    // กำหนดสีและป้ายกำกับตามระดับคะแนน
                    if ($avgRating >= 8) {
                        $badgeColor = '#10b981'; // เขียว (ดีมาก)
                        $badgeText = 'MUST WATCH';
                    } elseif ($avgRating >= 5) {
                        $badgeColor = '#f59e0b'; // ส้ม (ปานกลาง)
                        $badgeText = 'FAIR';
                    } else {
                        $badgeColor = '#ef4444'; // แดง (ไม่แนะนำ)
                        $badgeText = 'POOR';
                    }
                @endphp

                <a href="{{ route('activities.show', $activity->id) }}" style="text-decoration: none; color: inherit;">
                    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 14px; overflow: hidden; transition: transform 0.2s; position: relative;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                        
                        <!-- โปสเตอร์ -->
                        <div style="height: 260px; background: #0f172a; overflow: hidden; position: relative;">
                            @if($activity->image)
                                @if(\Illuminate\Support\Str::startsWith($activity->image, ['http://', 'https://']))
                                    <img src="{{ $activity->image }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="{{ asset('storage/' . $activity->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            @else
                                <div style="display: flex; height: 100%; align-items: center; justify-content: center; color: #475569; font-size: 40px;">🎬</div>
                            @endif

                            <!-- ป้ายบอกคะแนนเฉลี่ย -->
                            <div style="position: absolute; top: 10px; right: 10px; background: rgba(15, 23, 42, 0.85); border: 1px solid {{ $badgeColor }}; padding: 4px 10px; border-radius: 20px; backdrop-filter: blur(4px);">
                                <span style="color: {{ $badgeColor }}; font-weight: 800; font-size: 13px;">
                                    ⭐ {{ $avgRating > 0 ? $avgRating : 'N/A' }}
                                </span>
                            </div>

                            @if($avgRating > 0)
                                <span style="position: absolute; bottom: 10px; left: 10px; background: {{ $badgeColor }}; color: #0f172a; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 6px;">
                                    {{ $badgeText }}
                                </span>
                            @endif
                        </div>

                        <!-- รายละเอียด -->
                        <div style="padding: 15px;">
                            <div style="font-weight: 700; color: #fff; font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 6px;">
                                {{ $activity->name }}
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #94a3b8;">
                                <span>📁 {{ $activity->category }}</span>
                                <span>💬 {{ $activity->reviews_count }} รีวิว</span>
                            </div>
                        </div>

                    </div>
                </a>
            @endforeach
        </div>

        <!-- ตัวแบ่งหน้า (Pagination) -->
        <div style="margin-top: 30px;">
            {{ $activities->links() }}
        </div>
    @else
        <div style="background: #1e293b; border: 1px dashed #334155; border-radius: 14px; padding: 40px; text-align: center; color: #64748b;">
            ไม่พบข้อมูลหนังตามเงื่อนไขที่ค้นหา
        </div>
    @endif

</div>
@endsection