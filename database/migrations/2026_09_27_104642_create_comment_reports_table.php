<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ผู้รายงาน
            $table->foreignId('review_id')->constrained('reviews')->onDelete('cascade'); // คอมเมนต์ที่ถูกรายงาน
            $table->string('reason'); // เหตุผลในการรายงาน
            $table->string('status')->default('pending'); // สถานะ: pending, resolved, dismissed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_reports');
    }
};