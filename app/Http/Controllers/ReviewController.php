<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\CommentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // บันทึกความคิดเห็นพร้อมระบบสปอยล์
    public function store(Request $request, $activityId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id'     => Auth::id(),
            'activity_id' => $activityId,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
            'is_spoiler'  => $request->has('is_spoiler') ? 1 : 0, // 🟢 เช็กการติ๊กสปอยล์
        ]);

        return redirect()->back()->with('success', 'บันทึกความคิดเห็นเรียบร้อยแล้ว!');
    }

    // รายงานคอมเมนต์
    public function report(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        CommentReport::create([
            'user_id'   => Auth::id(),
            'review_id' => $id,
            'reason'    => $request->reason,
            'status'    => 'pending',
        ]);

        return redirect()->back()->with('success', 'ส่งรายงานความคิดเห็นเรียบร้อยแล้ว');
    }
}