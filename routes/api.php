<?php

use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\InquiryController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ScholarshipController;
use App\Http\Controllers\API\SubjectController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login']);

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{id}', [PostController::class, 'show']);

Route::get('cities', [CityController::class, 'index']);
Route::get('cities/{id}', [CityController::class, 'show']);

Route::get('subjects', [SubjectController::class, 'index']);
Route::get('subjects/{id}', [SubjectController::class, 'show']);
Route::get('majors', [SubjectController::class, 'index']);
Route::get('majors/{id}', [SubjectController::class, 'show']);

Route::get('scholarships', [ScholarshipController::class, 'index']);
Route::get('scholarships/{id}', [ScholarshipController::class, 'show']);

Route::get('settings', [\App\Http\Controllers\API\WebsiteSettingController::class, 'show']);

Route::post('inquiries', [InquiryController::class, 'store']);
