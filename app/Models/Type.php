<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    // ประเภทสื่อ 1 ประเภท มีหนังอยู่หลายเรื่อง
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
