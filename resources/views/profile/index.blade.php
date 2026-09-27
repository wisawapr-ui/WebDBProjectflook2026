@extends('layouts.app')

@section('title', 'โปรไฟล์ส่วนตัว')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 10px 0 40px 0; font-family: system-ui, -apple-system, sans-serif;">

    <!-- ปุ่มย้อนกลับ -->
    <div style="margin-bottom: 25px;">
        <a href="{{ route('activities.index') }}" style="color: #ff9900; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 153, 0, 0.1); padding: 8px 16px; border-radius: 20px; border: 1px solid rgba(255, 153, 0, 0.2); transition: all 0.2s;">
            ← กลับไปหน้ารายการหนัง
        </a>
    </div>

    <!-- 1. HEADER PROFILE CARD (ส่วนหัวโปรไฟล์สไตล์แก้ววิเศษ) -->
    <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 1px solid #334155; border-radius: 20px; padding: 30px; margin-bottom: 35px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        
        <div style="display: flex; align-items: center; gap: 25px;">
            <!-- อวาตาร์พร้อมไฟเรืองแสง -->
            <div style="position: relative;">
                <div style="width: 85px; height: 85px; background: linear-gradient(135deg, #ff9900, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; box-shadow: 0 0 20px rgba(255, 153, 0, 0.4); border: 3px solid #1e293b;">
                    👤
                </div>
            </div>

            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                    <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px;">
                        {{ $user->name }}
                    </h1>
                    <span style="background: rgba(255, 153, 0, 0.15); color: #ff9900; border: 1px solid #ff9900; font-size: 11px; padding: 2px 10px; border-radius: 12px; font-weight: 700;">
                        MEMBER
                    </span>
                </div>
                <p style="color: #94a3b8; margin: 0 0 8px 0; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                    ✉️ {{ $user->email }}
                </p>
                <div style="color: #64748b; font-size: 12px; font-weight: 500;">
                    🗓️ สมาชิกเมื่อ: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                </div>
            </div>
        </div>

        <!-- การ์ดสรุปสถิติ (Stats) -->
        <div style="display: flex; gap: 15px; width: auto;">
            <div style="background: rgba(15, 23, 42, 0.6); padding: 15px 22px; border-radius: 14px; text-align: center; border: 1px solid #334155; min-width: 100px;">
                <span style="font-size: 24px; font-weight: 800; color: #ff9900; display: block; line-height: 1.2;">
                    {{ $reviews->count() }}
                </span>
                <span style="font-size: 12px; color: #94a3b8; font-weight: 500;">คอมเมนต์</span>
            </div>
            
            <div style="background: rgba(15, 23, 42, 0.6); padding: 15px 22px; border-radius: 14px; text-align: center; border: 1px solid #334155; min-width: 100px;">
                <span style="font-size: 24px; font-weight: 800; color: #ef4444; display: block; line-height: 1.2;">
                    {{ isset($favoriteReviews) ? $favoriteReviews->count() : 0 }}
                </span>
                <span style="font-size: 12px; color: #94a3b8; font-weight: 500;">เรื่องโปรด</span>
            </div>
        </div>

    </div>

    <!-- 2. FAVORITE MOVIES SECTION (หนังเรื่องโปรด 8/10 ขึ้นไป) -->
    <div style="margin-bottom: 40px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <h2 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                ❤️ หนังที่ชื่นชอบ <span style="font-size: 13px; color: #64748b; font-weight: normal;">(ให้คะแนน 8 ดาวขึ้นไป)</span>
            </h2>
        </div>

        @if(isset($favoriteReviews) && $favoriteReviews->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
                @foreach($favoriteReviews as $fav)
                    @if($fav->activity)
                        <a href="{{ route('activities.show', $fav->activity->id) }}" style="text-decoration: none; color: inherit;">
                            <div style="background: #1e293b; border: 1px solid #334155; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-6px)'; this.style.borderColor='#ff9900';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#334155';">
                                
                                <!-- รูปโปสเตอร์หนัง -->
                                <div style="height: 250px; background: #0f172a; overflow: hidden; position: relative;">
                                    @if($fav->activity->image)
                                        @if(\Illuminate\Support\Str::startsWith($fav->activity->image, ['http://', 'https://']))
                                            <img src="{{ $fav->activity->image }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('storage/' . $fav->activity->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                    @else
                                        <div style="display: flex; height: 100%; align-items: center; justify-content: center; color: #475569; font-size: 40px;">🎬</div>
                                    @endif
                                    
                                    <!-- ป้ายคะแนนมุมขวาบน -->
                                    <span style="position: absolute; top: 10px; right: 10px; background: rgba(15, 23, 42, 0.85); color: #ff9900; font-weight: 800; font-size: 12px; padding: 4px 10px; border-radius: 20px; border: 1px solid #ff9900; backdrop-filter: blur(4px);">
                                        ⭐ {{ $fav->rating }}/10
                                    </span>
                                </div>

                                <!-- ข้อมูลรายละเอียด -->
                                <div style="padding: 15px;">
                                    <div style="font-weight: 700; color: #ffffff; font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 5px;">
                                        {{ $fav->activity->name }}
                                    </div>
                                    <div style="font-size: 12px; color: #94a3b8; display: flex; justify-content: space-between; align-items: center;">
                                        <span>📁 {{ $fav->activity->category }}</span>
                                        <span>📅 {{ $fav->activity->year }}</span>
                                    </div>
                                </div>

                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        @else
            <div style="background: #1e293b; border: 1px dashed #334155; border-radius: 14px; padding: 35px; text-align: center; color: #64748b;">
                🎬 ยังไม่มีรายการหนังที่ชื่นชอบ (ลองรีวิวหนังแล้วให้คะแนน 8 ดาวขึ้นไปดูนะครับ)
            </div>
        @endif
    </div>

    <!-- 3. REVIEW HISTORY SECTION (ประวัติการรีวิวและคอมเมนต์) -->
    <div>
        <h2 style="color: #ffffff; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            💬 ประวัติการคอมเมนต์ทั้งหมด
        </h2>

        @if(isset($reviews) && $reviews->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 18px;">
                @foreach($reviews as $review)
                    <div style="background: #1e293b; border: 1px solid #334155; border-left: 5px solid #ff9900; padding: 22px; border-radius: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                        
                        <!-- Header ของคอมเมนต์ -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                            <a href="{{ route('activities.show', $review->activity->id ?? '#') }}" style="color: #ff9900; font-weight: 700; text-decoration: none; font-size: 17px; display: flex; align-items: center; gap: 8px;">
                                🎬 {{ $review->activity->name ?? 'ไม่พบข้อมูลหนัง' }}
                            </a>
                            
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="background: #0f172a; color: #ff9900; font-weight: 800; font-size: 13px; padding: 5px 12px; border-radius: 20px; border: 1px solid rgba(255, 153, 0, 0.3);">
                                    ⭐ {{ $review->rating }} / 10
                                </span>
                            </div>
                        </div>

                        <!-- ป้ายแจ้งเตือนสปอยล์ (ถ้ามี) -->
                        @if($review->is_spoiler)
                            <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #f59e0b; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 12px; display: inline-flex; align-items: center; gap: 6px;">
                                ⚠️ มีเนื้อหาสปอยล์
                            </div>
                        @endif

                        <!-- เนื้อหาคอมเมนต์ -->
                        <p style="color: #cbd5e1; margin: 0 0 15px 0; font-size: 14px; line-height: 1.7; white-space: pre-line;">
                            {{ $review->comment }}
                        </p>

                        <!-- ฟุตเตอร์วันที่คอมเมนต์ -->
                        <div style="color: #64748b; font-size: 12px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; display: flex; justify-content: flex-end;">
                            🕒 {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div style="background: #1e293b; border: 1px dashed #334155; border-radius: 14px; padding: 35px; text-align: center; color: #64748b;">
                📝 คุณยังไม่เคยเขียนรีวิวหรือแสดงความคิดเห็นเรื่องใดเลย
            </div>
        @endif
    </div>

</div>
@endsection