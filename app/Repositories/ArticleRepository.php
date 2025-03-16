<?php

namespace App\Repositories;

use App\Models\{Article};
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Support\Facades\{Lang,Log};
class ArticleRepository implements ArticleRepositoryInterface
{

    public function getArticlesByUserPreferences($user)
    {
        $userPreferences = $user->preferences()->with(['authors', 'categories', 'sources'])->first();

        if (!$userPreferences) {
            return collect();
        }

        $authors = $userPreferences->authors->pluck('author')->toArray();
        $categories = $userPreferences->categories->pluck('id')->toArray();
        $sources = $userPreferences->sources->pluck('id')->toArray();

        $news =  Article::query()
                    ->when(!empty($authors), function ($query) use ($authors) {
                        $query->where(function ($q) use ($authors) {
                            foreach ($authors as $author) {
                                $q->orWhereRaw("REPLACE(author, 'By ', '') LIKE ?", ["%{$author}%"]);
                            }
                        });
                    })
                    ->when(!empty($categories), fn($query) => $query->whereIn('category_id', $categories))
                    ->when(!empty($sources), fn($query) => $query->whereIn('source_id', $sources))
                    ->paginate(10);

        $data['news'] = $news;

        return $data;
    }

    public function getArticles($request){

        $categories = $request->categories??[];
        $sources = $request->sources;
        $title = $request->title;

        $categories = is_string($categories) ? explode(',', $categories) : (is_array($categories) ? $categories : []);
        $sources = is_string($sources) ? explode(',', $sources) : (is_array($sources) ? $sources : []);

        $news =  Article::query()
                        ->when(!empty($title), function ($query) use ($title) {
                            $query->where('title', 'LIKE', "%{$title}%");
                        })
                        ->when(!empty($categories), fn($query) => $query->whereIn('category_id', $categories))
                        ->when(!empty($sources), fn($query) => $query->whereIn('source_id', $sources))
                        ->paginate(10);

        $data['news'] = $news;

        return $data;
    }

    public function getArticle($id){
        $article = Article::find($id);
        $data['article'] = $article;

        return $data;
    }
}
