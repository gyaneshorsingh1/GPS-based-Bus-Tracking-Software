<?php

return [

    'base_url' => env('GPS_BASE_URL'),

    'api_key' => env('GPS_API_KEY'),

    'heremaps_api_key' => env('HEREMAPS_API_KEY'),

    'timeout' => env('GPS_TIMEOUT', 10),

    'cache_ttl' => env('GPS_CACHE_TTL', 30),

    'offline_threshold_minutes' => env('GPS_OFFLINE_THRESHOLD_MINUTES', 10),

    'bus_started_cooldown_minutes' => env('GPS_STARTED_COOLDOWN_MINUTES', 180),

];
