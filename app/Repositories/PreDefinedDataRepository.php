<?php

namespace App\Repositories;
use App\Models\{Article,Category,Source};
use App\Repositories\Contracts\PreDefinedDataRepositoryInterface;

class PreDefinedDataRepository implements PreDefinedDataRepositoryInterface
{
    public function getAuthors() {
        return Article::distinct()
            ->pluck('author')
            ->map(fn ($author) => str_replace('By ', '', $author));
    }

    public function getCategories() {
        return Category::select('id', 'name')->status(1)->get();
    }

    public function getSources() {
        return Source::select('id', 'name')->status(1)->get();
    }
}
