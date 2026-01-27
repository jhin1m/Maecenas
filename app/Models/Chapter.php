<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $table = 'chapters';

    protected $fillable = [
        'name',
        'server',
        'comic_id',
        'slug',
        'chapter_number',
        'title',
        'content',
        'price',
    ];

    public function images()
    {
        return $this->hasMany(Image::class)->orderBy('page');
    }

    public function comic()
    {
        return $this->belongsTo(Comic::class, 'comic_id');
    }
}
