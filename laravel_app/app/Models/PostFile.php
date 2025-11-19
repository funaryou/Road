<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostFile extends Model
{
        protected $fillabe = [
        'post_id',
        'file_path',
    ];
}
