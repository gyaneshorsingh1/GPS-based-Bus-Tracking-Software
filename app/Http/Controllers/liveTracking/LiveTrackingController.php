<?php
namespace App\Http\Controllers\liveTracking;

use App\Http\Controllers\Controller;


class LiveTrackingController extends Controller
{
    public function index()
    {
        return view('liveTracking.view');
    }
}