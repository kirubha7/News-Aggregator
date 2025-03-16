<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;
/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     title="Article",
 *     description="Article model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Breaking News"),
 *     @OA\Property(property="content", type="string", example="This is the content of the article"),
 *     @OA\Property(property="author", type="string", example="John Doe"),
 *     @OA\Property(property="category_id", type="integer", example=2),
 *     @OA\Property(property="source_id", type="integer", example=5),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2025-03-16T12:00:00Z")
 * )
 */
class Article extends Model
{
    use ModelTrait;

    protected $fillable = [
        'id',
        'source_id',
        'category_id',
        'title',
        'description',
        'url',
        'published_at',
        'content',
        'author',
        'status',
        'view_count',
        'url',
        'image_url'
    ];


}
