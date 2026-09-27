<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hours',
        'category',
        'year',
        'review',
        'image',
        'is_approved',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'activity_id', 'user_id')->withTimestamps();
    }public function reviews(){
    return $this->hasMany(Review::class);
}

}