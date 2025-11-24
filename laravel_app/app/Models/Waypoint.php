<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waypoint extends Model
{
    protected $fillable = [
        'tour_id',
        'place_id',
        'day_number',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
