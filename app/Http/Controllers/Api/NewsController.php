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
