<x-app-layout page="live-tracking">

   <h2>Live Tracking</h2>

<p><strong>Success:</strong> {{ $liveData['success'] ? 'Yes' : 'No' }}</p>
<p><strong>Company ID:</strong> {{ $liveData['company_id'] }}</p>
<p><strong>Total Assets:</strong> {{ $liveData['count'] }}</p>

<hr>

@forelse ($liveData['data'] as $asset)
    @php($bus = $buses[$asset['imei']] ?? null)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <p><strong>Asset:</strong> {{ $asset['asset_name'] }}</p>
        @if ($bus)
            <p><strong>Bus:</strong> {{ $bus->bus_number }} <small>({{ $bus->registration_number }})</small></p>
            <p><strong>Route:</strong> {{ $bus->route?->name ?? '—' }}</p>
            <p><strong>Driver:</strong> {{ $bus->driver?->full_name ?? '—' }}</p>
            <p><strong>School:</strong> {{ $bus->school?->name ?? '—' }}</p>
        @endif
        <p><strong>Plate:</strong> {{ $asset['plate_number'] }}</p>
        <p><strong>Status:</strong> {{ $asset['status_label'] }}</p>
        <p><strong>Latitude:</strong> {{ $asset['latitude'] ?? 'N/A' }}</p>
        <p><strong>Longitude:</strong> {{ $asset['longitude'] ?? 'N/A' }}</p>
        <p><strong>Speed:</strong> {{ $asset['speed_kmh'] }} km/h</p>
        <p><strong>IMEI:</strong> {{ $asset['imei'] }}</p>

        <p><strong>Marker Color:</strong> {{ $asset['marker']['color'] }}</p>
        <p><strong>Moving:</strong> {{ $asset['is_moving'] ? 'Yes' : 'No' }}</p>
    </div>
@empty
    <p>No live assets match the buses you have access to.</p>
@endforelse
    </x-app-layout>
