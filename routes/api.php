<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);
Route::get('post', [PostController::class, 'index']);
Route::get('post/{id}/show', [PostController::class, 'show']);

Route::group(['middleware' => 'jwt.verify'], function(){
    Route::post('post/store', [PostController::class, 'store']);
    Route::put('post/{id}/update', [PostController::class, 'update']);
    Route::delete('post/{id}/destroy', [PostController::class, 'destroy']);
});
