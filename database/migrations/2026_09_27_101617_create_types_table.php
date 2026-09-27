<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อประเภท เช่น Movie, TV Series, Anime
            $table->string('slug')->unique(); // ตัวย่อภาษาอังกฤษสำหรับทำ URL
            $table->text('description')->nullable(); // คำอธิบายสังเขป
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};