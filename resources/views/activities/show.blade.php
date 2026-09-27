@extends('layouts.app')

@section('title', $activity->name)

@section('content')
<div style="max-width: 900px; margin: 0 auto;">

    <!-- ปุ่มย้อนกลับ -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('activities.index') }}" style="color: #ff9900; text-decoration: none; font-size: 14px; font-weight: bold;">
            ← กลับไปหน้ารายการหนัง
        </a>
    </div>

    <!-- ข้อความแจ้งเตือนเมื่อสำเร็จ -->
    @if(session('success'))
        <div style="background-color: #064e3b; color: #a7f3d0; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #059669;">
            {{ session('success') }}
        </div>
    @endif

    <!-- กล่องข้อมูลหลักของหนัง -->
    <div style="background: #111827; border: 1px solid #1f2937; border-radius: 8px; padding: 25px; margin-bottom: 30px;">
        <div style="display: flex; gap: 30px; flex-wrap: wrap;">
            
            <!-- รูปภาพโปสเตอร์ -->
            <div style="flex: 0 0 260px;">
                @if($activity->image)
                    @if(\Illuminate\Support\Str::startsWith($activity->image, ['http://', 'https://']))
                        <img src="{{ $activity->image }}" alt="{{ $activity->name }}" style="width: 100%; border-radius: 8px; border: 1px solid #374151;">
                    @else
                        <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->name }}" style="width: 100%; border-radius: 8px; border: 1px solid #374151;">
                    @endif
                @else
                    <div style="width: 100%; height: 350px; background: #1f2937; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6b7280;">
                        🎬 No Poster Available
                    </div>
                @endif
            </div>

            <!-- รายละเอียดหนัง -->
            <div style="flex: 1; min-width: 280px; color: #fff;">
                <h1 style="color: #ff9900; margin-top: 0; margin-bottom: 10px; font-size: 28px;">
                    {{ $activity->name }}
                </h1>

                <p style="color: #aaa; margin-bottom: 15px; font-size: 14px;">
                    📁 หมวดหมู่: <strong style="color: #fff;">{{ $activity->category }}</strong> | 
                    ⏱️ ความยาว: <strong style="color: #fff;">{{ $activity->hours }} ช.ม.</strong> | 
                    📅 ปีที่ฉาย: <strong style="color: #fff;">{{ $activity->year }}</strong>
                </p>

                <!-- กล่องแสดงคะแนนเฉลี่ย -->
                <div style="margin: 20px 0; background: #1a1a1a; padding: 15px; border-radius: 6px; border-left: 4px solid #ff9900;">
                    <span style="font-size: 13px; color: #888;">คะแนนเฉลี่ยจากผู้ใช้ทั้งหมด:</span><br>
                    <strong style="font-size: 26px; color: #ff9900;">
                        ⭐ {{ isset($activity->reviews) && $activity->reviews->count() > 0 ? number_format($activity->reviews->avg('rating'), 1) . ' / 10' : 'ยังไม่มีคะแนน' }}
                    </strong>
                    <span style="color: #888; font-size: 13px;">
                        ({{ isset($activity->reviews) ? $activity->reviews->count() : 0 }} ความคิดเห็น)
                    </span>
                </div>

                <h3 style="color: #fff; margin-bottom: 8px;">เรื่องย่อ / รายละเอียด:</h3>
                <p style="color: #d1d5db; line-height: 1.6; background: #1f2937; padding: 15px; border-radius: 6px; white-space: pre-line; margin-bottom: 0;">
                    {{ $activity->review ?? 'ยังไม่มีรายละเอียดเรื่องย่อ' }}
                </p>

                <!-- ปุ่มแก้ไข/ลบ สำหรับ Admin -->
                @auth
                    @if(Auth::user()->role === 'admin')
                        <div style="margin-top: 25px; padding-top: 15px; border-top: 1px solid #1f2937; display: flex; gap: 10px;">
                            <a href="{{ route('activities.edit', $activity->id) }}" style="background: #1e3a8a; color: #93c5fd; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px;">
                                ✏️ แก้ไขข้อมูล
                            </a>
                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบหนังเรื่องนี้?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #7f1d1d; color: #fecaca; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">
                                    🗑️ ลบข้อมูล
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- ส่วนเขียนรีวิว / คอมเมนต์ -->
    <div style="background: #111827; border: 1px solid #1f2937; border-radius: 8px; padding: 25px; margin-bottom: 30px;">
        <h3 style="color: #ff9900; margin-top: 0; margin-bottom: 15px;">✍️ เขียนรีวิว / แสดงความคิดเห็น</h3>

        @auth
            <form action="{{ route('reviews.store', $activity->id) }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label style="color: #ccc; display: block; margin-bottom: 5px; font-size: 14px;">คะแนนที่ให้ (1 - 10 ดาว):</label>
                    <select name="rating" required style="background: #1f2937; color: #fff; border: 1px solid #374151; padding: 8px 12px; border-radius: 4px; width: 100%; max-width: 200px;">
                        <option value="">-- เลือกคะแนน --</option>
                        @for($i = 10; $i >= 1; $i--)
                            <option value="{{ $i }}">⭐ {{ $i }} / 10</option>
                        @endfor
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: #ccc; display: block; margin-bottom: 5px; font-size: 14px;">ความคิดเห็นของคุณ:</label>
                    <textarea name="comment" rows="3" required placeholder="เขียนรีวิวหรือความรู้สึกหลังดูเรื่องนี้..." style="width: 100%; padding: 10px; background: #1f2937; color: #fff; border: 1px solid #374151; border-radius: 4px; box-sizing: border-box;"></textarea>
                </div>

                <!-- ช่องติ๊กเตือนสปอยล์ -->
                <div style="margin-bottom: 15px;">
                    <label style="color: #f59e0b; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="is_spoiler" value="1" style="width: 16px; height: 16px; cursor: pointer;">
                        ⚠️ เนื้อหานี้มีการสปอยล์ (เปิดเผยเนื้อหาสำคัญ)
                    </label>
                </div>

                <button type="submit" style="background: #ff9900; color: #000; border: none; padding: 10px 20px; font-weight: bold; border-radius: 4px; cursor: pointer;">
                    ส่งความคิดเห็น
                </button>
            </form>
        @else
            <div style="background: #1f2937; color: #aaa; padding: 15px; border-radius: 6px; text-align: center;">
                กรุณา <a href="{{ route('login') }}" style="color: #ff9900;">เข้าสู่ระบบ</a> เพื่อเขียนรีวิวและแสดงความคิดเห็น
            </div>
        @endauth
    </div>

    <!-- ส่วนแสดงรายการคอมเมนต์ -->
    <div style="background: #111827; border: 1px solid #1f2937; border-radius: 8px; padding: 25px;">
        <h3 style="color: #fff; margin-top: 0; margin-bottom: 20px;">💬 ความคิดเห็นทั้งหมด</h3>

        @if(isset($activity->reviews) && $activity->reviews->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($activity->reviews as $review)
                    <div style="background: #1f2937; border: 1px solid #374151; padding: 15px; border-radius: 6px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div style="font-weight: bold; color: #ff9900;">
                                👤 {{ $review->user->name ?? 'ผู้ใช้ทั่วไป' }}
                            </div>
                            <div style="color: #f59e0b; font-weight: bold; font-size: 14px;">
                                ⭐ {{ $review->rating }} / 10
                            </div>
                        </div>

                        <!-- ตรวจสอบการสปอยล์ -->
                        @if($review->is_spoiler)
                            <details style="background: #281d13; border: 1px solid #f59e0b; padding: 12px; border-radius: 6px; margin: 10px 0;">
                                <summary style="color: #f59e0b; font-weight: bold; font-size: 14px; cursor: pointer; user-select: none;">
                                    ⚠️ ความคิดเห็นนี้มีเนื้อหาสปอยล์ (คลิกที่นี่เพื่อดูเนื้อหา)
                                </summary>
                                <div style="color: #d1d5db; margin-top: 10px; font-size: 14px; line-height: 1.5; white-space: pre-line; border-top: 1px dashed #f59e0b; padding-top: 10px;">
                                    {{ $review->comment }}
                                </div>
                            </details>
                        @else
                            <p style="color: #d1d5db; margin: 10px 0; font-size: 14px; line-height: 1.5; white-space: pre-line;">
                                {{ $review->comment }}
                            </p>
                        @endif

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; border-top: 1px solid #374151; padding-top: 8px;">
                            <span style="color: #6b7280; font-size: 11px;">
                                {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                            </span>

                            <!-- ปุ่มรายงานคอมเมนต์ -->
                            @auth
                                <details style="position: relative;">
                                    <summary style="color: #ef4444; font-size: 12px; cursor: pointer; user-select: none;">
                                        🚨 รายงานคอมเมนต์
                                    </summary>
                                    <form action="{{ route('reviews.report', $review->id) }}" method="POST" style="position: absolute; right: 0; bottom: 25px; background: #111827; border: 1px solid #374151; padding: 12px; border-radius: 6px; width: 240px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); z-index: 10;">
                                        @csrf
                                        <label style="color: #ccc; font-size: 12px; display: block; margin-bottom: 5px;">เหตุผลที่รายงาน:</label>
                                        <select name="reason" required style="width: 100%; background: #1f2937; color: #fff; border: 1px solid #374151; padding: 5px; border-radius: 4px; font-size: 12px; margin-bottom: 8px;">
                                            <option value="คำหยาบคาย / ไม่สุภาพ">คำหยาบคาย / ไม่สุภาพ</option>
                                            <option value="สแปม / โฆษณา">สแปม / โฆษณา</option>
                                            <option value="สปอยล์เนื้อหาโดยไม่เตือน">สปอยล์เนื้อหาโดยไม่เตือน</option>
                                            <option value="เนื้อหาไม่เหมาะสมอื่นๆ">เนื้อหาไม่เหมาะสมอื่นๆ</option>
                                        </select>
                                        <button type="submit" style="width: 100%; background: #dc2626; color: #fff; border: none; padding: 5px; border-radius: 4px; font-size: 12px; font-weight: bold; cursor: pointer;">
                                            ส่งรายงาน
                                        </button>
                                    </form>
                                </details>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; color: #888; padding: 30px 0;">
                ยังไม่มีความคิดเห็นสำหรับหนังเรื่องนี้
            </div>
        @endif
    </div>

</div>
@endsection