<?php

use App\Http\Controllers\AccountSessionController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\UserMovieController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel API erreichbar'
    ]);
});

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/details', [MovieController::class, 'details']);
Route::get('/reviews/{imdbID}', [UserMovieController::class, 'indexReviews']);

Route::post('/users', [UserRegistrationController::class, 'store']);
Route::get('/accounts/session', [AccountSessionController::class, 'show']);
Route::post('/accounts/session', [AccountSessionController::class, 'store']);
Route::delete('/accounts/session', [AccountSessionController::class, 'destroy']);

Route::middleware(['auth:sanctum', 'owns.route.user'])->group(function () {
    Route::get('/users/{user}/watchlist', [UserMovieController::class, 'indexWatchlist']);
    Route::post('/users/{user}/watchlist', [UserMovieController::class, 'storeWatchlist']);
    Route::delete('/users/{user}/watchlist/{imdbID}', [UserMovieController::class, 'destroyWatchlist']);
    Route::post('/users/{user}/watchedlist/{imdbID}', [UserMovieController::class, 'moveToWatched']);
    Route::get('/users/{user}/watchedlist', [UserMovieController::class, 'indexWatchedlist']);
    Route::get('/users/{user}/watchedlist/{imdbID}/review', [UserMovieController::class, 'showReview']);
    Route::post('/users/{user}/watchedlist/{imdbID}/review', [UserMovieController::class, 'storeReview']);
    Route::delete('/users/{user}/watchedlist/{imdbID}', [UserMovieController::class, 'destroyWatchedlist']);
});
