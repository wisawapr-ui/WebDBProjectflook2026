<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'rating',
        'comment',
        'is_spoiler',
    ];

    protected $casts = [
        'is_spoiler' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function reports()
    {
        return $this->hasMany(CommentReport::class);
    }
}