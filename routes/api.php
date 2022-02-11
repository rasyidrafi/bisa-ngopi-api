<?php

use App\Http\Controllers\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
});

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::post('/auth/logout', [App\Http\Controllers\AuthController::class, 'logout']);

    Route::get('/menu', [App\Http\Controllers\MenuController::class, "index"]);
    Route::post('/menu', [App\Http\Controllers\MenuController::class, "store"]);
    Route::get('/menu/{id}', [App\Http\Controllers\MenuController::class, "show"]);
    Route::put('/menu/{id}', [App\Http\Controllers\MenuController::class, "update"]);
    Route::delete('/menu/{id}', [App\Http\Controllers\MenuController::class, "destroy"]);
});
