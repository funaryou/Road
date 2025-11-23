<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'days',
        'place',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function waypoints()
    {
        return $this->hasMany(Waypoint::class);
    }
}
