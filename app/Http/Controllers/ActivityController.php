<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Review;
use App\Models\CommentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
   public function index(Request $request)
    {
        $sort = $request->get('sort', 'latest');
        $category = $request->get('category');
        $search = $request->get('search');

        $query = Activity::withAvg('reviews', 'rating')
                         ->withCount('reviews');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($sort === 'top_rated') {
            $query->orderByDesc('reviews_avg_rating');
        } elseif ($sort === 'lowest_rated') {
            $query->orderBy('reviews_avg_rating', 'asc');
        } elseif ($sort === 'most_reviewed') {
            $query->orderByDesc('reviews_count');
        } else {
            $query->latest();
        }

        $activities = $query->paginate(12);

        // 🟢 1. เพิ่มบรรทัดนี้เพื่อดึงหมวดหมู่หนังทั้งหมดที่มีในระบบ
        $categories = Activity::whereNotNull('category')->select('category')->distinct()->pluck('category');

        // 🟢 2. เพิ่ม 'categories' ใน compact(...) ด้านล่างนี้
        return view('activities.index', compact('activities', 'categories', 'sort', 'category', 'search'));
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'hours' => 'required|numeric',
            'year' => 'required|integer',
            'review' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('activities', 'public');
        }

        Activity::create([
            'name' => $request->name,
            'category' => $request->category,
            'hours' => $request->hours,
            'year' => $request->year,
            'review' => $request->review,
            'image' => $imagePath,
        ]);

        return redirect()->route('activities.index')->with('success', 'เพิ่มข้อมูลหนังเรียบร้อยแล้ว');
    }

    public function show($id)
    {
        $activity = Activity::with(['reviews.user'])->findOrFail($id);
        return view('activities.show', compact('activity'));
    }

    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'hours' => 'required|numeric',
            'year' => 'required|integer',
            'review' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }
            $activity->image = $request->file('image')->store('activities', 'public');
        }

        $activity->update([
            'name' => $request->name,
            'category' => $request->category,
            'hours' => $request->hours,
            'year' => $request->year,
            'review' => $request->review,
            'image' => $activity->image,
        ]);

        return redirect()->route('activities.show', $activity->id)->with('success', 'อัปเดตข้อมูลหนังเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'ลบหนังเรียบร้อยแล้ว');
    }

  
    public function profile()
    {
        $user = Auth::user();
        $reviews = Review::where('user_id', Auth::id())->with('activity')->latest()->get();
        
        // 🟢 เปลี่ยนจาก 'profile' เป็น 'profile.index'
        return view('profile.index', compact('user', 'reviews'));
    }

    // บันทึกรีวิว/ความคิดเห็น (พร้อมสปอยล์)
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'activity_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_spoiler' => $request->has('is_spoiler') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'บันทึกความคิดเห็นเรียบร้อยแล้ว!');
    }

    // บันทึกการรายงานคอมเมนต์
    public function reportReview(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        CommentReport::create([
            'user_id' => Auth::id(),
            'review_id' => $id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'ส่งรายงานความคิดเห็นที่ไม่เหมาะสมเรียบร้อยแล้ว');
    }
    public function stats()
{
    // 1. คำนวณคะแนนเฉลี่ยจำแนกตามหมวดหมู่หนัง
    $categoryData = Activity::withAvg('reviews', 'rating')
        ->get()
        ->groupBy('category')
        ->map(function ($items, $category) {
            $avg = $items->pluck('reviews_avg_rating')->filter()->avg();
            return [
                'category' => $category,
                'avg_rating' => round($avg ?? 0, 1),
                'count' => $items->sum('reviews_count'),
            ];
        })
        ->sortByDesc('avg_rating')
        ->values();

    // 2. หนังที่มีคะแนนเฉลี่ยสูงสุด 5 อันดับแรก (เฉพาะที่มีคนรีวิวแล้ว)
    $topMovies = Activity::withAvg('reviews', 'rating')
        ->has('reviews')
        ->orderByDesc('reviews_avg_rating')
        ->take(5)
        ->get(['name', 'reviews_avg_rating']);

    // 3. หนังที่มีคะแนนเฉลี่ยต่ำสุด 5 อันดับแรก
    $lowestMovies = Activity::withAvg('reviews', 'rating')
        ->has('reviews')
        ->orderBy('reviews_avg_rating', 'asc')
        ->take(5)
        ->get(['name', 'reviews_avg_rating']);

    return view('activities.stats', compact('categoryData', 'topMovies', 'lowestMovies'));
}
}