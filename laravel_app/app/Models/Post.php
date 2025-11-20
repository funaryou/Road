<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
    ];

    // 投稿を作成したユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 投稿に紐づくファイル
    public function files()
    {
        return $this->hasMany(PostFile::class);
    }
}
