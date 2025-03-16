<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;
/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     title="Article",
 *     description="Article model representing a news article",
 *     required={"id", "title", "source_id", "status"},
 *     @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(property="title", type="string", maxLength=191, example="Breaking News: Laravel Releases New Update"),
 *     @OA\Property(property="description", type="string", example="A short summary of the article"),
 *     @OA\Property(property="content", type="string", example="Full content of the article goes here..."),
 *     @OA\Property(property="author", type="string", maxLength=191, nullable=true, example="John Doe"),
 *     @OA\Property(property="url", type="string", format="url", nullable=true, example="https://news.example.com/article/123"),
 *     @OA\Property(property="image_url", type="string", format="url", nullable=true, example="https://news.example.com/article/image.jpg"),
 *     @OA\Property(property="source_id", type="string", format="uuid", example="a1b2c3d4-e5f6-7890-abcd-ef1234567890"),
 *     @OA\Property(property="category_id", type="string", format="uuid", nullable=true, example="b2c3d4e5-f678-90ab-cdef-234567890abc"),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true, example="2025-03-16T17:51:59+05:30"),
 *     @OA\Property(property="status", type="integer", example=1, description="1 = Active, 0 = Inactive"),
 *     @OA\Property(property="view_count", type="integer", example=100),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example="2025-03-16T18:00:00+05:30"),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true, example="2025-03-16T16:00:00+05:30"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example="2025-03-16T16:30:00+05:30")
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
