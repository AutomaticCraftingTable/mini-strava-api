<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json($request->user()->activities()->with('positions')->get());
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'nullable|string',
            'activity_type' => 'required|in:bicycle,run,walk',
            'distance' => 'required|numeric',
            'time' => 'required|integer',
            'pace' => 'required|numeric',
            'speed' => 'required|numeric',
            'track' => 'required|array',
            'track.*.latitude' => 'required|numeric',
            'track.*.longitude' => 'required|numeric',
        ]);

        $track = $data['track'];
        unset($data['track']);

        $activity = $request->user()->activities()->create($data);
        $activity->positions()->createMany($track);

        return response()->json($activity->load('positions'), 201);
    }

    /**
     * Display the specified activity.
     */
    public function show(Request $request, $id)
    {
        $activity = Activity::with('positions')->findOrFail($id);

        // Ensure the user owns the activity (optional, based on your privacy requirements)
        // if ($request->user()->id !== $activity->user_id) {
        //     abort(403);
        // }

        return response()->json($activity);
    }

    /**
     * Remove the specified activity from storage.
     */
    public function destroy(Request $request, $id)
    {
        $activity = $request->user()->activities()->find($id);

        if (! $activity) {
            return response()->json(['message' => "Couldn't delete activity. Activity with id $id not found."], 404);
        }

        $activity->delete();

        return response()->json(['message' => 'Activity deleted.']);
    }

    /**
     * Export the activity to a GPX file.
     */
    public function export($id)
    {
        $activity = Activity::with('positions')->findOrFail($id);

        $gpxContent = $this->generateGpx($activity);

        return Response::make($gpxContent, 200, [
            'Content-Type' => 'application/gpx+xml',
            'Content-Disposition' => 'attachment; filename="activity-' . $activity->id . '.gpx"',
        ]);
    }

    /**
     * Export all activities to a GPX file.
     */
    public function exportAll(Request $request)
    {
        $activities = $request->user()->activities()->with('positions')->get();

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><gpx version="1.1" creator="MiniStrava API" xmlns="http://www.topografix.com/GPX/1/1"></gpx>');

        foreach ($activities as $activity) {
            $this->addTrackToGpx($xml, $activity);
        }

        return Response::make($xml->asXML(), 200, [
            'Content-Type' => 'application/gpx+xml',
            'Content-Disposition' => 'attachment; filename="all-activities.gpx"',
        ]);
    }

    /**
     * Helper to generate GPX XML string.
     */
    private function generateGpx(Activity $activity)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><gpx version="1.1" creator="MiniStrava API" xmlns="http://www.topografix.com/GPX/1/1"></gpx>');
        $this->addTrackToGpx($xml, $activity);

        return $xml->asXML();
    }

    private function addTrackToGpx(\SimpleXMLElement $xml, Activity $activity)
    {
        $trk = $xml->addChild('trk');
        $trk->addChild('name', htmlspecialchars($activity->title));
        
        $trkseg = $trk->addChild('trkseg');

        foreach ($activity->positions as $point) {
            $trkpt = $trkseg->addChild('trkpt');
            $trkpt->addAttribute('lat', $point->latitude);
            $trkpt->addAttribute('lon', $point->longitude);
        }
    }
}