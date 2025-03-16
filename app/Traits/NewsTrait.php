<?php

namespace App\Traits;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Http, Log};

trait NewsTrait
{
    public function fetchNews($url, $params)
    {
        Log::info("Fetching news from {$url}");
        Log::info("Params: ".json_encode($params));

        $response = Http::withOptions([
                    'verify' => storage_path('cacert.pem')
                    ])
                    ->get($url, $params);
        return $response->json();
    }

}
