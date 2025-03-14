<?php

namespace  App\Helpers;
use Carbon\Carbon;

class NYTimesAPI extends NewsAPIBase
{
    protected function getApiParams($source, $category, $page)
    {
        return [
            'api-key'    => $source->api_key,
            'begin_date' => Carbon::now()->subHours(24)->format('Ymd'),
            'end_date'   => Carbon::now()->format('Ymd'),
            'page'       => $page,
            'q'          => $category->name
        ];
    }

    protected function extractArticles($response)
    {
        $articles =  $response['response']['docs'] ?? [];
        $articleData = [];
        foreach ($articles as $article) {
            $articleData[] = [
                'title'       => $article['headline']['main'] ?? null,
                'description' => $article['snippet'] ?? null,
                'url'         => $article['web_url'] ?? null,
                'published_at'=> Carbon::parse($article['pub_date'] ?? now())->format('Y-m-d H:i:s'),
                'content'     => $article['snippet'] ?? null,
                'author'      => $article['byline']['original'] ?? null,
                'image_url'   => $article['multimedia']['url'] ?? null,
                'status'      => 1
            ];
        }
        return $articleData;
    }

    protected function validateResponse($response)
    {
        return is_array($response) && isset($response['status']) && $response['status'] === 'OK';
    }
}
