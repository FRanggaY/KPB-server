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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//API route for register new user
// Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);

//login user
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //for admin create acc
    Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);

    //user
    Route::get('/profile', [App\Http\Controllers\API\ProfileController::class, 'getProfile']);
    Route::patch('/update-profile', [App\Http\Controllers\API\ProfileController::class, 'updateProfile']);
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);


    //additional user
    Route::post('/profile-detail', [App\Http\Controllers\API\ProfileDetailController::class, 'createProfileDetail']);
    Route::patch('/profile-detail', [App\Http\Controllers\API\ProfileDetailController::class, 'updateProfileDetail']);


    Route::get('/users', [App\Http\Controllers\API\ProfileController::class, 'showAllUsers']);

    //PROFILE WEBSITE (VISI, MISI, DESKRIPSI)
    // Route::resource('/profile-web', App\Http\Controllers\API\ProfileDescController::class)->except(['create', 'edit', 'update', 'show', 'destroy']);
    Route::get('/profile-web', [App\Http\Controllers\API\ProfileDescController::class, 'index']);
    Route::post('/profile-web', [App\Http\Controllers\API\ProfileDescController::class, 'store']);
    Route::patch('/update-profile-web', [App\Http\Controllers\API\ProfileDescController::class, 'update']);
    // Route::delete('/delete-profile-web', [App\Http\Controllers\API\ProfileDescController::class, 'destroy']);

    //Gallery
    Route::resource('/gallery', App\Http\Controllers\API\GalleryController::class)->except(['create', 'edit', 'show']);
});


