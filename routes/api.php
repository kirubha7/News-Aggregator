<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\{RegisterController, LoginController,ForgotPasswordController,ResetPasswordController};
use App\Http\Controllers\Api\{UserController,NewsController};


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {

    //Logout
    Route::post('logout', [LoginController::class, 'logout']);

    //Pre Defined API's
    Route::get('authors',[UserController::class, 'authors']);
    Route::get('category',[UserController::class, 'category']);
    Route::get('sources',[UserController::class, 'sources']);

    //User
    Route::get('user', [UserController::class, 'userDetails']);
    Route::post('user/preferences', [UserController::class, 'updatePreferences']);

    //Articles
    Route::get('news/preferences',[NewsController::class,'getArticlesByUserPreferences']);
    Route::get('news/feed',[NewsController::class,'getArticles']);
    Route::get('news/feed/{id}',[NewsController::class,'getArticle']);


});
