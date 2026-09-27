<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Movie (ภาพยนตร์โรง/สตรีมมิ่ง)', 'slug' => 'movie', 'description' => 'ภาพยนตร์ความยาวสมบูรณ์'],
            ['name' => 'TV Series (ซีรีส์)', 'slug' => 'tv-series', 'description' => 'ละคร หรือซีรีส์หลายตอน'],
            ['name' => 'Anime (แอนิเมชัน)', 'slug' => 'anime', 'description' => 'การ์ตูนและแอนิเมชัน'],
            ['name' => 'Short Film (หนังสั้น)', 'slug' => 'short-film', 'description' => 'ภาพยนตร์ความยาวสั้น'],
        ];

        foreach ($types as $type) {
            Type::firstOrCreate(['slug' => $type['slug']], $type);
        }
    }
}