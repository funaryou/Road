<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'google_place_id',
        'name',
        'lat',
        'lng',
        'image_url',
        'rating',
    ];

    public function waypoints()
    {
        return $this->hasMany(Waypoint::class);
    }
    public function placeLikes(){
        return $this->belongsToMany(User::class, 'place_likes', 'place_id', 'user_id')->withTimestamps();
    }
}
