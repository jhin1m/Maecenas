<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'content',
        'user_id',
        'comic_id',
        'story_id',
        'chapter_id',
        'parent_id',
    ];

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'replies')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comic()
    {
        return $this->belongsTo(Comic::class, 'comic_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
    }
}
