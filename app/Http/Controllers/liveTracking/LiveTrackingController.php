<?php
namespace App\Http\Controllers\liveTracking;

use App\Http\Controllers\Controller;
use App\Services\NazarTrackService;

class LiveTrackingController extends Controller
{
    public function index(NazarTrackService $nazarTrack)
    {
        $live = $nazarTrack->live();

        return view('liveTracking.view', [
            'liveData' => $live,
        ]);

        // return response()->json($devices);

        // dd($devices);
    }
}