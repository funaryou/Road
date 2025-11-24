<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Place;
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
            $latestWaypoint = $tour->waypoints()->with('place')->latest()->first();
            $tour->topImage = $latestWaypoint && $latestWaypoint->place ? $latestWaypoint->place->image_url : null;
            return $tour;
        });
        return view("app.tour.index", ["tours" => $tours]);
    }
    
    public function showTour($id)
    {
        $tour = Tour::with('waypoints.place')->find($id);
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
        return redirect()->route("tour.show", ["id" => $tour->id]);
    }
    public function addWaypointForm($id)
    {
        $tour = Tour::findOrFail($id);
        return view("app.tour.waypoint.form", ["tour" => $tour]);
    }   
    public function addWaypoint(Request $request, $id)
    {
        $tour = Tour::find($id);
        
        $waypointsData = json_decode($request->input('waypoints_json', '[]'), true);
        
        $dayNumber = $request->input('days', 1);
        
        foreach ($waypointsData as $data) {
            // Find or create place
            $place = Place::firstOrCreate(
                ['google_place_id' => $data['google_place_id']],
                [
                    'name' => $data['name'],
                    'lat' => $data['lat'],
                    'lng' => $data['lng'],
                    'image_url' => $data['image_url'] ?? null,
                    'rating' => $data['rating'] ?? null,
                ]
            );
            
            // Create waypoint with place_id
            $tour->waypoints()->create([
                'place_id' => $place->id,
                'day_number' => $dayNumber,
            ]);
        }

        return redirect()->route("tour.show", ["id" => $tour->id, "day" => $dayNumber]);
    }
}