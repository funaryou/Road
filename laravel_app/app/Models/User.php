<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'icon',
        'is_company',
    ];

    // ユーザーがいいねした投稿
    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function tours(){
        return $this->hasMany(Tour::class, 'user_id', 'id');
    }
    function posts(){
        return $this->hasMany(Post::class, 'user_id', 'id');
    }
    function comments(){
        return $this->hasMany(comment::class, 'user_id', 'id');
    }
    // Posts that this user has liked
    function likes(){
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id')->withTimestamps();
    }
    function placeLikes(){
        return $this->belongsToMany(Place::class, 'place_likes', 'user_id', 'place_id')->withTimestamps();
    }
    
    // Users that this user is following
    function following(){
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id')->withTimestamps();
    }
    
    // Users that are following this user
    function followers(){
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id')->withTimestamps();
    }

}
