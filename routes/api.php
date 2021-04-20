<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login/user', [\App\Http\Controllers\Api\UserController::class, 'login']);
Route::post('login/supervisor', [\App\Http\Controllers\Api\SupervisorController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('login/user/me', [\App\Http\Controllers\Api\UserController::class, 'me']);
    Route::post('login/user/refresh', [\App\Http\Controllers\Api\UserController::class, 'refresh']);
    Route::post('login/user/logout', [\App\Http\Controllers\Api\UserController::class, 'logout']);
});

Route::middleware('auth:supervisor')->group(function () {
    Route::get('login/supervisor/me', [\App\Http\Controllers\Api\SupervisorController::class, 'me']);
    Route::post('login/supervisor/refresh', [\App\Http\Controllers\Api\SupervisorController::class, 'refresh']);
    Route::post('login/supervisor/logout', [\App\Http\Controllers\Api\SupervisorController::class, 'logout']);
});
