<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title', 'body', 'image', 'category_id', 'user_id', 'views', 'status', 'approved_by', 'approved_at', 'rejection_reason'];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
