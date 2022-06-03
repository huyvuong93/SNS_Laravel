<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'status',
        'user_id',
    ];

    protected $dates = [
        'created_at'
    ];

    protected $appends = [
        'createdTime'
    ];

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->with('user')
            ->withCount('likes')
            ->take(3);
    }

    public function getCreatedTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
