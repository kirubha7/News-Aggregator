<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DateTimeImmutable;
use Guardian\GuardianAPI;
class NewsController extends Controller
{
    public function newsAPI(Request $request){

        $source = $request->query('source');

        // Define API endpoints and parameters
        $apiConfig = [
            'guardian' => [
                'url' => 'https://content.guardianapis.com/search',
                'params' => [
                    'api-key' => env('GUARDIAN_API_KEY'),
                    'tag' => 'film/film,tone/reviews',
                    'from-date' => '2025-01-01',
                    'to-date' => now()->toDateString(),
                    'order-by' => 'relevance',
                    'show-fields' => 'starRating,headline,thumbnail,short-url',
                    'show-tags' => 'contributor',
                    'q' => 'cinema',
                    'page' => 1,
                    'page-size' => 20
                ]
            ],
            'newsapi' => [
                'url' => 'https://newsapi.org/v2/top-headlines',
                'params' => [
                    'apiKey' => env('NEWS_API_KEY'),
                    'pageSize' => 5,
                    'page' => 1,
                    'category' => 'sports'
                ]
            ]
        ];

        // Fetch data using Laravel HTTP Client
        $response = Http::withOptions(['verify' => storage_path('cacert.pem')])
            ->get($apiConfig[$source]['url'], $apiConfig[$source]['params']);

        return $response->json();
    }

}
