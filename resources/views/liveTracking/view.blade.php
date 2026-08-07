<x-app-layout page="live-tracking">

   <h2>Live Tracking</h2>

<p><strong>Success:</strong> {{ $liveData['success'] ? 'Yes' : 'No' }}</p>
<p><strong>Company ID:</strong> {{ $liveData['company_id'] }}</p>
<p><strong>Total Assets:</strong> {{ $liveData['count'] }}</p>

<hr>

@foreach ($liveData['data'] as $asset)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <p><strong>Asset:</strong> {{ $asset['asset_name'] }}</p>
        <p><strong>Plate:</strong> {{ $asset['plate_number'] }}</p>
        <p><strong>Status:</strong> {{ $asset['status_label'] }}</p>
        <p><strong>Latitude:</strong> {{ $asset['latitude'] ?? 'N/A' }}</p>
        <p><strong>Longitude:</strong> {{ $asset['longitude'] ?? 'N/A' }}</p>
        <p><strong>Speed:</strong> {{ $asset['speed_kmh'] }} km/h</p>
        <p><strong>IMEI:</strong> {{ $asset['imei'] }}</p>

        <p><strong>Marker Color:</strong> {{ $asset['marker']['color'] }}</p>
        <p><strong>Moving:</strong> {{ $asset['is_moving'] ? 'Yes' : 'No' }}</p>
    </div>
@endforeach
    </x-app-layout>