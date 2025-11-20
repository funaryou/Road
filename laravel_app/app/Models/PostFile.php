<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostFile extends Model
{
    protected $fillable = [
        'post_id',
        'file_path',
    ];

    // 所属する投稿
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
