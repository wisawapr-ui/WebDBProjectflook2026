<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // ผูก FK type_id ไปที่ตาราง types (อนุญาตให้เป็น NULL ได้ชั่วคราวเผื่อกรณีหนังเก่าที่ยังไม่ระบุประเภท)
            $table->foreignId('type_id')->nullable()->after('id')->constrained('types')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });
    }
};