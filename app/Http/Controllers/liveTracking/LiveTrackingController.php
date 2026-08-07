<?php
namespace App\Http\Controllers\liveTracking;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Services\NazarTrackService;
use Illuminate\Support\Facades\Auth;

class LiveTrackingController extends Controller
{
    public function index(NazarTrackService $nazarTrack)
    {
        $user = Auth::user();

        $allowedBusIds = null;

        if ($user->hasRole('Parent')) {
            $parent = ParentProfile::where('user_id', $user->id)->first();
            $allowedBusIds = $parent
                ? $parent->children()->pluck('bus_id')->filter()
                : collect();
        } elseif ($user->hasRole('Driver')) {
            $driver = Driver::where('user_id', $user->id)->first();
            $allowedBusIds = $driver ? $driver->buses()->pluck('id') : collect();
        } elseif ($user->hasRole('School Admin') || $user->hasRole('Principal')) {
            $schoolId = $user->school_id
                ?? SchoolAdmin::where('user_id', $user->id)->value('school_id')
                ?? School::where('principal_name', $user->name)->value('id');
            $allowedBusIds = $schoolId
                ? Bus::where('school_id', $schoolId)->pluck('id')
                : collect();
        }

        $buses = Bus::query()
            ->with(['driver', 'route', 'school'])
            ->when($allowedBusIds !== null, fn ($query) => $query->whereIn('id', $allowedBusIds))
            ->get();

        $busByImei = $buses
            ->whereNotNull('gps_device_id')
            ->keyBy('gps_device_id');

        $live = $nazarTrack->live();

        $assets = collect($live['data'] ?? []);

        $filteredAssets = $assets
            ->filter(fn ($asset) => isset($busByImei[$asset['imei'] ?? null]))
            ->values();

        $live['data'] = $filteredAssets->all();
        $live['count'] = $filteredAssets->count();

        return view('liveTracking.view', [
            'liveData' => $live,
            'buses' => $busByImei,
        ]);
    }
}
