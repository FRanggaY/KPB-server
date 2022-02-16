<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;

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

// Route::get('/school', [SchoolController::class, 'index']);
// Route::post('/school', [SchoolController::class, 'store']);
// Route::get('/school/{id}', [SchoolController::class, 'show']);
// Route::put('/school/{id}', [SchoolController::class, 'update']);
// Route::delete('/school/{id}', [SchoolController::class, 'destroy']);

Route::resource('/school', SchoolController::class)->except(['create', 'edit']);
