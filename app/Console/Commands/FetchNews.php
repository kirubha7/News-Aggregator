<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Source};
use Illuminate\Support\Facades\{Log};
use App\Helpers\{NewsApi, GuardianAPI, NYTimesAPI};

class FetchNews extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news articles from external sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $sources = Source::status(1)->get();
            foreach ($sources as $source) {
                $articles = $this->fetchFromSource($source);
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
            Log::error($e->getMessage());
        }
    }

    private function fetchFromSource($source)
    {
        return match ($source->name) {
        'NEWS API' => (new NewsApi())->fetchArticles($source),
            'The Guardian' => (new GuardianAPI())->fetchArticles($source),
            'The New York Times' => (new NYTimesAPI())->fetchArticles($source),
            default => [],
        };
    }
}
