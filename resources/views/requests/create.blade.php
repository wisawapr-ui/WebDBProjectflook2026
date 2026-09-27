@extends('layouts.app')

@section('title', 'ส่งคำขอเพิ่มหนัง')

@section('content')
    <div style="max-width: 600px; margin: 0 auto; background: #111; padding: 25px; border-radius: 8px; border: 1px solid #333;">
        <a href="{{ route('activities.index') }}" style="color: #888; text-decoration: none; display: inline-block; margin-bottom: 15px;">← กลับไปหน้าหลัก</a>
        
        <h2 style="color: #ff9900; margin-top: 0; margin-bottom: 20px;">🎬 ส่งคำขอเพิ่มหนังใหม่ (รอแอดมินอนุมัติ)</h2>

        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ต้องใส่ enctype="multipart/form-data" เสมอเมื่อมีการส่งไฟล์ -->
        <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #ccc; margin-bottom: 5px;">ชื่อเรื่อง (Movie Name):</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="เช่น Interstellar" style="width: 100%; padding: 10px; background: #222; color: #fff; border: 1px solid #444; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #ccc; margin-bottom: 5px;">ความยาวหนัง (ชั่วโมง):</label>
                <input type="number" step="0.1" name="hours" required value="{{ old('hours') }}" placeholder="เช่น 2.5" style="width: 100%; padding: 10px; background: #222; color: #fff; border: 1px solid #444; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #ccc; margin-bottom: 5px;">หมวดหมู่ (Category):</label>
                <input type="text" name="category" required value="{{ old('category') }}" placeholder="เช่น Action, Sci-Fi" style="width: 100%; padding: 10px; background: #222; color: #fff; border: 1px solid #444; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #ccc; margin-bottom: 5px;">ปีที่หนังฉาย (Release Year):</label>
                <input type="number" name="year" value="{{ old('year') }}" placeholder="เช่น 2024" style="width: 100%; padding: 10px; background: #222; color: #fff; border: 1px solid #444; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #ccc; margin-bottom: 5px;">เรื่องย่อ / รายละเอียดหนัง:</label>
                <textarea name="review" rows="4" placeholder="พิมพ์เรื่องย่อ หรือรายละเอียดสั้นๆ..." style="width: 100%; padding: 10px; background: #222; color: #fff; border: 1px solid #444; border-radius: 4px; box-sizing: border-box;">{{ old('review') }}</textarea>
            </div>

            <!-- กล่อง Drag & Drop อัปโหลดรูปภาพ -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #ccc; margin-bottom: 5px;">รูปโปสเตอร์หนัง:</label>
                <div id="drop-zone" style="border: 2px dashed #ff9900; border-radius: 8px; padding: 25px; text-align: center; background: #1a1a1a; cursor: pointer; color: #ccc; transition: 0.2s;">
                    <p style="margin: 0; font-size: 15px;">📁 ลากไฟล์รูปภาพมาวางที่นี่ หรือ <span style="color: #ff9900; text-decoration: underline;">คลิกเพื่อเลือกไฟล์</span></p>
                    <span id="file-name-display" style="display: block; margin-top: 10px; color: #4caf50; font-weight: bold; font-size: 14px;"></span>
                    
                    <!-- Input File ซ่อนไว้หลังกล่อง Drag & Drop -->
                    <input type="file" name="image" id="image-input" accept="image/*" style="display: none;">
                </div>
            </div>

            <button type="submit" style="width: 100%; background-color: #ff9900; color: #000; border: none; padding: 12px; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 16px;">
                🚀 ส่งคำขอไปยัง Admin
            </button>
        </form>
    </div>

    <!-- Script ควบคุมการ ลาก-วาง ไฟล์ -->
    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('image-input');
        const fileNameDisplay = document.getElementById('file-name-display');

        // คลิกกล่อง Drag & Drop เพื่อเลือกไฟล์
        dropZone.addEventListener('click', () => fileInput.click());

        // แสดงชื่อไฟล์เมื่อกดเลือกผ่าน Dialog
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = '✓ เลือกไฟล์แล้ว: ' + fileInput.files[0].name;
            }
        });

        // เมื่อลากไฟล์เข้ามาบนกล่อง
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.background = '#2a2a2a';
            dropZone.style.borderColor = '#ffffff';
        });

        // เมื่อลากไฟล์ออกจากกล่อง
        dropZone.addEventListener('dragleave', () => {
            dropZone.style.background = '#1a1a1a';
            dropZone.style.borderColor = '#ff9900';
        });

        // เมื่อปล่อยไฟล์ลงในกล่อง (Drop)
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.background = '#1a1a1a';
            dropZone.style.borderColor = '#ff9900';

            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files; // ยัดไฟล์ใส่ input
                fileNameDisplay.textContent = '✓ เลือกไฟล์แล้ว: ' + e.dataTransfer.files[0].name;
            }
        });
    </script>
@endsection