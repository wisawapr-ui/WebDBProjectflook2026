@extends('layouts.app')

@section('title', 'แก้ไขข้อมูลหนัง')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: #111827; padding: 25px; border-radius: 8px; border: 1px solid #1f2937;">
    <h2 style="color: #ff9900; margin-top: 0;">✏️ แก้ไขข้อมูลหนัง</h2>

    @if ($errors->any())
        <div style="background-color: #7f1d1d; color: #fecaca; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- ชื่อหนัง -->
        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #ccc; margin-bottom: 5px;">ชื่อหนัง/ซีรีส์:</label>
            <input type="text" name="name" value="{{ old('name', $activity->name) }}" required style="width: 100%; padding: 10px; background: #1f2937; color: #fff; border: 1px solid #374151; border-radius: 4px; box-sizing: border-box;">
        </div>

        <!-- ความยาว (ชั่วโมง) -->
        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #ccc; margin-bottom: 5px;">ความยาว (ชั่วโมง):</label>
            <input type="number" step="0.1" name="hours" value="{{ old('hours', $activity->hours) }}" required style="width: 100%; padding: 10px; background: #1f2937; color: #fff; border: 1px solid #374151; border-radius: 4px; box-sizing: border-box;">
        </div>

        <!-- หมวดหมู่ -->
        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #ccc; margin-bottom: 5px;">หมวดหมู่ (เช่น Action, Horror):</label>
            <input type="text" name="category" value="{{ old('category', $activity->category) }}" required style="width: 100%; padding: 10px; background: #1f2937; color: #fff; border: 1px solid #374151; border-radius: 4px; box-sizing: border-box;">
        </div>

        <!-- ปีที่ฉาย -->
        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #ccc; margin-bottom: 5px;">ปีที่ฉาย (ค.ศ.):</label>
            <input type="number" name="year" value="{{ old('year', $activity->year) }}" required style="width: 100%; padding: 10px; background: #1f2937; color: #fff; border: 1px solid #374151; border-radius: 4px; box-sizing: border-box;">
        </div>

        <!-- เรื่องย่อ / คำอธิบาย -->
        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #ccc; margin-bottom: 5px;">เรื่องย่อ / คำอธิบาย:</label>
            <textarea name="review" rows="4" style="width: 100%; padding: 10px; background: #1f2937; color: #fff; border: 1px solid #374151; border-radius: 4px; box-sizing: border-box;">{{ old('review', $activity->review) }}</textarea>
        </div>

        <!-- อัปโหลดรูปภาพใหม่ -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #ccc; margin-bottom: 5px;">เปลี่ยนรูปโปสเตอร์ (เว้นว่างไว้หากใช้รูปเดิม):</label>
            @if($activity->image)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->name }}" style="max-height: 100px; border-radius: 4px;">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" style="width: 100%; color: #ccc;">
        </div>

        <button type="submit" style="background: #ff9900; color: #000; border: none; padding: 10px 20px; font-weight: bold; border-radius: 4px; cursor: pointer; width: 100%;">
            อัปเดตข้อมูล
        </button>
    </form>
</div>
@endsection