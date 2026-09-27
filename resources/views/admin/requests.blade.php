@extends('layouts.app')

@section('title', 'จัดการคำขอเพิ่มหนัง')

@section('content')
    <div style="max-width: 1000px; margin: 0 auto;">
        <a href="{{ route('activities.index') }}" style="color: #888; text-decoration: none; display: inline-block; margin-bottom: 15px;">← กลับไปหน้าหลัก</a>
        
        <h2 style="color: #ff5252; margin-top: 0; margin-bottom: 20px;">📋 รายการคำขอเพิ่มหนังจากสมาชิก (รอการอนุมัติ)</h2>

        @if(session('success'))
            <div class="alert-success" style="margin-bottom: 15px;">{{ session('success') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Poster</th>
                    <th>Movie Name</th>
                    <th>Category</th>
                    <th>Hours</th>
                    <th>Synopsis</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingMovies as $movie)
                    <tr>
                        <td>
                            @if($movie->image)
                                <img src="{{ asset('storage/' . $movie->image) }}" style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px;">
                            @else
                                <span style="color: #666; font-size: 12px;">No Poster</span>
                            @endif
                        </td>
                        <td><strong style="color: #fff;">{{ $movie->name }}</strong></td>
                        <td>{{ $movie->category }}</td>
                        <td>{{ $movie->hours }} ชม.</td>
                        <td style="max-width: 250px; font-size: 13px; color: #aaa;">{{ Str::limit($movie->review, 80) }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <form action="{{ route('admin.requests.approve', $movie->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background-color: #4caf50; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                                        ✓ อนุมัติ
                                    </button>
                                </form>

                                <form action="{{ route('admin.requests.reject', $movie->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background-color: #d32f2f; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
                                        ✕ ปฏิเสธ
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #888; padding: 30px;">ไม่มีคำขอเพิ่มหนังค้างอยู่</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection