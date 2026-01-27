<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterImg extends Model
{
    use HasFactory;
    protected $table = 'chapterimgs';
    protected $fillable = ['link', 'page'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
