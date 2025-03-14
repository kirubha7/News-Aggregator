<?php

namespace App\Helpers;
use Carbon\Carbon;

class GuardianAPI extends NewsAPIBase
{
    protected function getApiParams($source, $category, $page)
    {
        return [
            'api-key'      => $source->api_key,
            'from-date'    => Carbon::now()->subHour(20)->toIso8601String(),
            'to-date'      => Carbon::now()->toIso8601String(),
            'page'         => $page,
            'q'           => $category->name,
            'show-fields'  => 'thumbnail',
            'show-tags'    => 'contributor',
            'page-size'    => 50
        ];
    }

    protected function extractArticles($response)
    {
        $articles = $response['response']['results'] ?? [];
        $articleData = [];
        foreach ($articles as $article) {
            $articleData[] = [
                'title' => $article['webTitle'],
                'description' => $article['webTitle'],
                'url' => $article['webUrl'],
                'published_at' => Carbon::parse($article['webPublicationDate'] ?? now())->format('Y-m-d H:i:s'),
                'content' => $article['webTitle'],
                'author' => $article['fields']['byline'] ?? 'Unknown',
                'image_url' => $article['fields']['thumbnail'] ?? null,
                'status' => 1
            ];
        }
        return $articleData;
    }


    protected function validateResponse($response)
    {
        return is_array($response) && isset($response['response']['status']) && $response['response']['status'] === 'ok';
    }
}
