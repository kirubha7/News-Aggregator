<?php

namespace App\Helpers;

use App\Models\{Source, Article, Category};
use Illuminate\Support\Facades\{Log};
use App\Traits\NewsTrait;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;

abstract class NewsAPIBase
{
    use NewsTrait;

    abstract protected function getApiParams($source, $category, $page);
    abstract protected function extractArticles($response);
    abstract protected function validateResponse($response);

    public function fetchArticles($source)
    {
        try {
            Log::info("Fetching news from {$source->name}");
            $categories = Category::where('status', 1)->get();
            foreach ($categories as $category) {
                $this->fetchNewsRecursively($source, $category, 1);
            }
            Log::info("News fetched successfully from {$source->name}");
            return true;
        } catch (Exception $e) {
            Log::error("Error fetching news from {$source->name}: " . $e->getMessage());
            return false;
        }
    }

    private function fetchNewsRecursively($source, $category, $page = 1)
    {
        $params = $this->getApiParams($source, $category, $page);
        $response = $this->fetchNews($source->api_url, $params);
        if (!$this->validateResponse($response)) {
            Log::warning("Invalid response for category: {$category->name}, Page: $page", ['response' => $response]);
            return;
        }

        $articles = $this->extractArticles($response);
        if (empty($articles)) {
            Log::info("No articles found for category: {$category->name} on page $page");
            return;
        }

        $this->processArticles($articles, $source, $category);

        if ($page < 5) { // Limit recursion to 5 pages
            sleep(2);
            $this->fetchNewsRecursively($source, $category, $page + 1);
        }
    }

    private function processArticles($articles, $source, $category)
    {
        $articleData = [];
        foreach ($articles as $article) {
            $articleData[] = [
                'id'            => Str::uuid(),
                'source_id'     => $source->id,
                'category_id'   => $category->id,
                'published_at'  => $article['published_at'],
                'url'           => $article['url'] ?? null,
                'image_url'     => $article['image_url'] ?? null,
                'content'       => $article['content'] ?? null,
                'description'   => $article['description'] ?? null,
                'title'         => $article['title'] ?? null,
                'author'        => $article['author'] ?? 'Unknown',
                'status'        => 1,
            ];
        }

        try{
            Article::upsert($articleData, ['source_id', 'title']);
        }catch(Exception $e){
            Log::error("Error processing articles: " . $e->getMessage());
        }
    }
}
