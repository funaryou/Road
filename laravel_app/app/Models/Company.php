<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $fillable = [
        'admin_id',
        'name',
        'address',
        'home_page_url',
        'number',
        'email',
        'biography',
    ];
}
