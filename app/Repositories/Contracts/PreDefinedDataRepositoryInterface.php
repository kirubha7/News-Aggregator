<?php

namespace App\Repositories\Contracts;

interface PreDefinedDataRepositoryInterface
{
    public function getAuthors();
    public function getCategories();
    public function getSources();
}
