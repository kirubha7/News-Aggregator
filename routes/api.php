<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\{RegisterController, LoginController,ForgotPasswordController,ResetPasswordController};
use App\Http\Controllers\Api\{UserController};

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user', [UserController::class, 'userDetails']);
});
