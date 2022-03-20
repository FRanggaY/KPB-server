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

// Route::middleware(['auth:sanctum','IsAdmin'])->group(function () {
//     Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
// });

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['IsAdmin'] , function () {
        Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
        Route::delete('/delete-user/{id}', [App\Http\Controllers\API\ProfileController::class, 'delete']);
    });
    //user
    Route::get('/profile', [App\Http\Controllers\API\ProfileController::class, 'getProfile']);
    Route::patch('/update-profile', [App\Http\Controllers\API\ProfileController::class, 'updateProfile']);
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);


    //additional user
    Route::patch('/change-password', [App\Http\Controllers\API\AuthController::class, 'changePassword']);
    Route::post('/profile-detail', [App\Http\Controllers\API\ProfileDetailController::class, 'createProfileDetail']);
    Route::patch('/profile-detail', [App\Http\Controllers\API\ProfileDetailController::class, 'updateProfileDetail']);

    //PROFILE WEBSITE (VISI, MISI, DESKRIPSI)
    // Route::resource('/profile-web', App\Http\Controllers\API\ProfileDescController::class)->except(['create', 'edit', 'update', 'show', 'destroy']);
    // Route::post('/profile-web', [App\Http\Controllers\API\ProfileDescController::class, 'store']);
    // Route::patch('/update-profile-web', [App\Http\Controllers\API\ProfileDescController::class, 'update']);
    // Route::delete('/delete-profile-web', [App\Http\Controllers\API\ProfileDescController::class, 'destroy']);

    //Gallery
    Route::resource('/gallery', App\Http\Controllers\API\GalleryController::class)->except(['create', 'edit', 'show', 'index']);

    //Activities
    Route::resource('/activities', App\Http\Controllers\API\ActivitiesController::class)->except(['create', 'edit', 'show', 'index']);

    //Advertisement
    Route::resource('/advertisement', App\Http\Controllers\API\AdvertisementController::class)->except(['create', 'edit', 'show', 'index']);


});

//users
Route::get('/users/detail/{id}', [App\Http\Controllers\API\ProfileController::class, 'showAllUsers']);
Route::get('/users/{id}', [App\Http\Controllers\API\ProfileController::class, 'showAllUsersPaginate']);
Route::get('/users', [App\Http\Controllers\API\ProfileController::class, 'showAllUsersList']);
//Gallery
Route::get('/gallery', [App\Http\Controllers\API\GalleryController::class, 'index']);
Route::get('/gallery/{id}', [App\Http\Controllers\API\GalleryController::class, 'show']);
//Activities
Route::get('/activities', [App\Http\Controllers\API\ActivitiesController::class, 'index']);
Route::get('/activities/{id}', [App\Http\Controllers\API\ActivitiesController::class, 'show']);
//Advertisement
Route::get('/advertisement', [App\Http\Controllers\API\AdvertisementController::class, 'index']);
Route::get('/advertisement/{id}', [App\Http\Controllers\API\AdvertisementController::class, 'show']);
//Profile Website
Route::get('/profile-web', [App\Http\Controllers\API\ProfileDescController::class, 'index']);

