<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('hours');
            $table->string('category');
            $table->integer('year')->nullable(); // 👈 เพิ่มช่องเก็บปีที่หนังฉาย
            $table->text('review')->nullable();  // เรื่องย่อ/คำอธิบายหนัง
            $table->text('image')->nullable();   // ลิงก์รูปโปสเตอร์
            $table->boolean('is_approved')->default(false);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};