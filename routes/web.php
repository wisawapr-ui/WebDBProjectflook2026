<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('activities.index') : redirect()->route('login');
});
Route::get('/stats', [ActivityController::class, 'stats'])->name('activities.stats');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // รายการหนังหน้าหลัก
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

    // ผู้ใช้ส่งคำขอเพิ่มหนัง
    Route::get('/movie-request', [ActivityController::class, 'createRequest'])->name('requests.create');
    Route::post('/movie-request', [ActivityController::class, 'storeRequest'])->name('requests.store');

    // โซนจัดการสำหรับ Admin (ต้องวาง /activities/create ก่อน /activities/{id})
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/admin/requests', [ActivityController::class, 'adminRequests'])->name('admin.requests');
        Route::post('/admin/requests/{id}/approve', [ActivityController::class, 'approveRequest'])->name('admin.requests.approve');
        Route::post('/admin/requests/{id}/reject', [ActivityController::class, 'rejectRequest'])->name('admin.requests.reject');

        Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/activities/store', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('/activities/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
        Route::put('/activities/{id}', [ActivityController::class, 'update'])->name('activities.update');
        Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    });

    // หน้ารายละเอียดหนัง (ต้องวางล่างสุด เพื่อป้องกัน Route {id} ชนกับ /create)
    Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');
    Route::post('/activities/{id}/reviews', [ActivityController::class, 'storeReview'])->name('activities.reviews.store');
});
Route::middleware(['auth'])->group(function () {
    // หน้าโปรไฟล์ผู้ใช้
    Route::get('/profile', [App\Http\Controllers\ActivityController::class, 'profile'])->name('profile.index');
    
    // ปุ่มกดเพิ่ม/ยกเลิก หนังเรื่องโปรด
    Route::post('/activities/{id}/favorite', [App\Http\Controllers\ActivityController::class, 'toggleFavorite'])->name('activities.favorite');
});
Route::post('/activities/{id}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

Route::post('/reviews/{id}/report', [App\Http\Controllers\ActivityController::class, 'reportReview'])->name('reviews.report')->middleware('auth');

use App\Http\Controllers\ReviewController;

Route::middleware(['auth'])->group(function () {
    Route::post('/activities/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{id}/report', [ReviewController::class, 'report'])->name('reviews.report');
});

