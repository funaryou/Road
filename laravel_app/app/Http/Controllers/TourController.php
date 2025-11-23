<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Http\Requests\TourStoreRequest;

class TourController extends Controller
{
    public function tourForm()
    {
        return view('app.tour.form');
    }

    public function tourSelect()
    {
        $user = auth()->user();
        $tours = Tour::where('user_id', $user->id)
            ->orWhereHas('user', function ($query) {
                $query->where('is_company', true);
            })
            ->get();
        $tours = $tours->map(function ($tour) {
            $tour->waypointsCount = $tour->waypoints()->count();
            return $tour;
        });
        $tours = $tours->map(function ($tour) {
            $tour->topImage = $tour->waypoints()->latest()->first()->image_url;
            return $tour;
        });
        return view("app.tour.index", ["tours" => $tours]);
    }
    
    public function showTour($id)
    {
        $tour = Tour::with('waypoints')->find($id);
        return view("app.tour.show", ["tour" => $tour]);
    }

    public function tourStore(TourStoreRequest $request)
    {
        $user = request()->user();
        $name = $request->input("title");
        $days = $request->input("days");
        $place = $request->input("place");
        $destination = $request->input("destination");
        
        $tour = $user->tours()->create([
            "name" => $name,
            "days" => $days,
            "place" => $place,
            "destination" => $destination,
        ]);
        return redirect()->route("waypoint.form", ["id" => $tour->id]);
    }
    public function addWaypointForm($id)
    {
        $tour = Tour::find($id);
        return view("app.tour.waypoint.form", ["tour" => $tour]);
    }   
    public function addWaypoint(Request $request, $id)
    {
        $tour = Tour::find($id);
        
        $waypointsData = json_decode($request->input('waypoints_json', '[]'), true);
        
        $dayNumber = $request->input('days', 1);
        
        $waypointsToCreate = collect($waypointsData)->map(function ($data) use ($dayNumber) {
            return [
                "name" => $data['name'],
                "day_number" => $dayNumber,
                "lat" => $data['lat'],
                "lng" => $data['lng'],
                "google_place_id" => $data['google_place_id'] ?? null,
                "image_url" => $data['image_url'] ?? null,
                "rating" => $data['rating'] ?? null,
            ];
        })->all();

        if (!empty($waypointsToCreate)) {
            $tour->waypoints()->createMany($waypointsToCreate);
        }

        return redirect()->route("waypoint.form", ["id" => $tour->id]);
    }
}