<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'voting';

    protected $fillable = [
        'user_id',
        'story_id',
        'comic_id',
        'vote',
    ];
}
