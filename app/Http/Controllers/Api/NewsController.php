<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Repositories\Contracts\{ArticleRepositoryInterface};
use Illuminate\Support\Facades\{Lang,Log};

class NewsController extends Controller
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/news/preferences",
     *     summary="Get Articles Based on User Preferences",
     *     description="Retrieves news articles based on the user's saved preferences (authors, categories, and sources).",
     *     operationId="getArticlesByUserPreferences",
     *     tags={"News"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Preferences-based news retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Article")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function getArticlesByUserPreferences(){
        try{
            Log::info('NewsController@getArticlesByUserPreferences: Get Preferences News');

            $data = $this->articleRepository->getArticlesByUserPreferences(auth()->user());

            // Return success response
            return ResponseHelper::success(Lang::get('messages.preferences_news'), $data, 200);
        }catch(\Exception $e){
            Log::error('NewsController@getArticlesByUserPreferences: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }


    /**
         * @OA\Get(
         *     path="/api/news/feed",
         *     summary="Get News Articles",
         *     description="Retrieves news articles based on filters such as title, categories, and sources.",
         *     operationId="getArticles",
         *     tags={"News"},
         *     @OA\Parameter(
         *         name="title",
         *         in="query",
         *         description="Search articles by title",
         *         required=false,
         *         @OA\Schema(type="string")
         *     ),
         *     @OA\Parameter(
         *         name="categories",
         *         in="query",
         *         description="Filter by categories (comma-separated IDs)",
         *         required=false,
         *         @OA\Schema(type="string", example="1,2,3")
         *     ),
         *     @OA\Parameter(
         *         name="sources",
         *         in="query",
         *         description="Filter by sources (comma-separated IDs)",
         *         required=false,
         *         @OA\Schema(type="string", example="5,10")
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Success",
         *         @OA\JsonContent(
         *             @OA\Property(property="success", type="boolean", example=true),
         *             @OA\Property(property="message", type="string", example="News articles retrieved successfully"),
         *             @OA\Property(property="data", type="array",
         *                 @OA\Items(ref="#/components/schemas/Article")
         *             )
         *         )
         *     ),
         *     @OA\Response(
         *         response=500,
         *         description="Internal Server Error"
         *     )
         * )
     */
    public function getArticles(Request $request){
        try{
            Log::info('NewsController@getArticles: Get Preferences News');

            $data = $this->articleRepository->getArticles($request);

            // Return success response
            return ResponseHelper::success(Lang::get('messages.news'), $data, 200);
        }catch(\Exception $e){
            Log::error('NewsController@getArticles: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }

           /**
     * @OA\Get(
     *     path="/api/news/feed/{id}",
     *     summary="Get a Single Article",
     *     description="Retrieves a single news article by its ID.",
     *     operationId="getArticle",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the news article",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Article retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Article")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function getArticle(Request $request,$id){
        try{
            Log::info('NewsController@getArticle: Get Preferences News');

            $data = $this->articleRepository->getArticle($id);

            // Return success response
            return ResponseHelper::success(Lang::get('messages.news'), $data, 200);
        }catch(\Exception $e){
            Log::error('NewsController@getArticle: ' . $e->getMessage());

            // Return error response
            return ResponseHelper::error($e->getMessage(), [], 500);
        }
    }

}
