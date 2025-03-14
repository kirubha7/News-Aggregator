<?php

namespace App\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NewsApi extends NewsAPIBase
{
    protected function getApiParams($source, $category, $page)
    {
        return [
            'apiKey'    => $source->api_key,
            'from'      => Carbon::now()->subHour(24)->toIso8601String(),
            'to'        => Carbon::now()->toIso8601String(),
            'page'      => $page,
            'pageSize'  => 50,
            'q'         => $category->name
        ];
    }

    protected function extractArticles($response)
    {
        Log::info($response);
        return $response['articles'] ?? [];
    }

    protected function validateResponse($response)
    {
        return is_array($response) && isset($response['status']) && $response['status'] === 'ok';
    }
}

