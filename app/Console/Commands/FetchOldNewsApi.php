<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Source, Article, Category};
use Illuminate\Support\{Str, Http, Carbon};
use Illuminate\Support\Facades\Log;
use App\Traits\NewsTrait;
use Exception;

class FetchOldNewsApi extends Command
{
    use NewsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-old-news-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news articles from NEWS API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            Log::info("Fetching old news from NEWS API");
            $source = Source::where('name', 'NEWS API')->firstOrFail();
            $categories = Category::where('status', 1)->get();

            foreach ($categories as $category) {
                $this->fetchNewsRecursively($source, $category, 1);
            }
        } catch (Exception $e) {
            $this->error("Error in FetchOldNewsApi Scheduler: " . $e->getMessage());
            Log::error("Error in FetchOldNewsApi Scheduler: " . $e->getMessage(), ['exception' => $e]);
        }
    }

    /**
     * Recursive function to fetch news
     */
    private function fetchNewsRecursively($source, $category, $page = 1)
    {
        $this->info("Fetching news for category: {$category->name}, Page: $page");

        $response = $this->fetchNews($source->api_url, [
            'apiKey'    => $source->api_key,
            'from'      => Carbon::now()->subHour(1)->format('YYYY-MM-DDTHH:MM:SSZ'),
            'to'        => Carbon::now()->format('YYYY-MM-DDTHH:MM:SSZ'),
            'page'      => $page,
            'pageSize'  => 1,
            'q'         => $category->name
        ]);

        if (!$this->isValidResponse($response)) {
            Log::warning("Invalid response for category: {$category->name}, Page: $page", ['response' => $response]);
            return;
        }

        $totalResults = $response['totalResults'] ?? 0;
        $articles = $response['articles'] ?? [];
        $totalPages = ceil($totalResults / 100);

        if (empty($articles)) {
            $this->info("No articles found for category: {$category->name} on page $page");
            return;
        }

        $this->processArticles($articles, $source, $category);

        if ($page < $totalPages) {
            sleep(2);
            $this->fetchNewsRecursively($source, $category, $page + 1);
        }
    }

    /**
     * Validate API response
     */
    private function isValidResponse($response)
    {
        return is_array($response) && isset($response['status']) && $response['status'] === 'ok';
    }

    /**
     * Process and store articles
     */
    private function processArticles($articles, $source, $category)
    {
        foreach ($articles as $article) {
            $articleData = [
                'id'            => Str::uuid(),
                'source_id'     => (string) $source->id,
                'category_id'   => (string) $category->id,
                'published_at'  => Carbon::parse($article['publishedAt'] ?? now())->format('Y-m-d H:i:s'),
                'url'           => $article['url'] ?? null,
                'image'         => $article['urlToImage'] ?? null,
                'content'       => $article['content'] ?? null,
                'description'   => $article['description'] ?? null,
                'title'         => $article['title'] ?? null,
                'author'        => $article['author'] ?? null,
                'status'        => 1,
            ];
            Article::updateOrCreate(
                ['source_id' => (string) $source->id, 'title' => $articleData['title']],
                $articleData
            );
        }
    }

}
