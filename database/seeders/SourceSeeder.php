<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Source;
use Illuminate\Support\Str;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sourceData = [
            [
                'id' => Str::uuid(),
                'name' => 'NEWS API',
                'url' => 'https://newsapi.org/',
                'api_url' => 'https://newsapi.org/v2/everything',
                'api_key' => 'ab65547e1144449d81efe44a12f597ba',
                'api_secret' => null,
                'default_params' => json_encode([
                    'apiKey' => env('NEWS_API_KEY'),
                    'from' => '2025-02-15T00:00:00',
                    'to' => '2025-03-12T23:59:59',
                    'q' => 'sports',
                    'page' => 1
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'status' => 1
                ],
            [
                'id' => Str::uuid(),
                'name' => 'The Guardian',
                'url' => 'https://open-platform.theguardian.com/documentation/',
                'api_url' => 'https://content.guardianapis.com/search',
                'api_key' => '346a8716-d9ee-48bf-9094-332c2175e9bc',
                'api_secret' => null,
                'default_params' => json_encode([
                    'country' => 'us',
                    'apiKey' => env('GUARDIAN_API_KEY'),
                    'pageSize' => 5,
                    'page' => 1,
                    'category' => 'sports'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'status' => 1
            ],
            [
                'id' => Str::uuid(),
                'name' => 'The New York Times',
                'url' => 'https://developer.nytimes.com/apis',
                'api_url' => 'https://api.nytimes.com/svc/search/v2/articlesearch.json',
                'api_key' => env('NYTIMES_API_KEY'),
                'api_secret' => env('NYTIMES_API_KEY'),
                'default_params' => json_encode([
                    "q"=> "election",
                    "api-key" => env('NYTIMES_API_KEY')
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'status' => 1
            ]
        ];
        Source::insert($sourceData);
    }
}
