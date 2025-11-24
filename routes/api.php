<?php

use App\Http\Controllers\API\InquiryController;
use App\Http\Controllers\API\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;

Route::post('login', [LoginController::class, 'login']);
// Route::get('posts', [PostController::class, 'index']);
Route::resource('posts', PostController::class);
Route::resource('inquiries', InquiryController::class);
// Route::middleware('auth:api')->group(function () {
// });
