<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillabe = [
        'user_id',
        'content',
    ];
}
