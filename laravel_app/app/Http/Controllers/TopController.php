<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopController extends Controller
{
    // トップページ
    public function top()
    {
        return view('app.top.index');
    }
}