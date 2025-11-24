<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Place;

class TopController extends Controller
{
    // トップページ
    public function top()
    {
        return view('app.top.index');
    }

    public function likePlace(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'google_place_id' => 'required|string',
            'name' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'image_url' => 'nullable|string',
            'rating' => 'nullable|numeric',
        ]);

        // Find or create the place
        $place = Place::firstOrCreate(
            ['google_place_id' => $data['google_place_id']],
            [
                'name' => $data['name'],
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'image_url' => $data['image_url'],
                'rating' => $data['rating'],
            ]
        );

        // Toggle like
        $user->placeLikes()->toggle($place->id);
        
        $isLiked = $user->placeLikes()->where('place_id', $place->id)->exists();

        return response()->json(['liked' => $isLiked]);
    }
}