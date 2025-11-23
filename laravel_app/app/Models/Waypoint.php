<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waypoint extends Model
{
    protected $fillable = [
        'tour_id',
        'name',
        'day_number',
        'google_place_id',
        'lat',
        'lng',
        'image_url',
    ];
}
