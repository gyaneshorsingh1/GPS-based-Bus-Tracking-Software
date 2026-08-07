<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NazarTrackService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.nazartrack.base_url');
        $this->apiKey = config('services.nazartrack.api_key');
    }

    protected function client()
    {
        return Http::withToken($this->apiKey)
            ->acceptJson()
            ->baseUrl($this->baseUrl . '/api/ext');
    }

    public function me()
    {
        return $this->client()->get('/me')->json();
    }

    // public function devices()
    // {
    //     return $this->client()->get('/devices')->json();
    // }

      public function live()
    {
        return $this->client()->get('/live-tracking')->json();
    }
}