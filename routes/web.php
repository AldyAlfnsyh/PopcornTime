<?php

use App\Http\Controllers\MoviesController;
use App\Http\Controllers\MovieTvController;
use App\Http\Controllers\TvController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MovieTvController::class, 'index']);
Route::get('/list-movie-tv', [MovieTvController::class, 'list_movie_tv'])->name('list-movie-tv');;
Route::get('/movie/{id}', [MoviesController::class, 'movie_detail']);
Route::get('/tv/{id}', [TvController::class, 'tv_detail']);
Route::get('/tv/{tv_id}/season', [TvController::class, 'tv_list_season']);
Route::get('/tv/{tv_id}/season/{number_season}', [TvController::class, 'tv_list_episode']);
Route::get('/tv/{tv_id}/season/{number_season}/episode/{number_episode}', [TvController::class, 'tv_episode_detail']);

Route::get('/upcoming-movie', [MoviesController::class, 'list_upcoming_movie']);
Route::get('/on-the-air-tv', [TvController::class, 'list_on_the_air_tv']);

Route::get('/genre/{id}/list-movie-tv', [MovieTvController::class, 'list_movie_tv_byGenre']);
Route::get('/director/{id}/list-movie-tv', [MovieTvController::class, 'list_movie_tv_byDirector']);
Route::get('/writer/{id}/list-movie-tv', [MovieTvController::class, 'list_movie_tv_byWriter']);
Route::get('/cast/{id}/list-movie-tv', [MovieTvController::class, 'list_movie_tv_byCast']);
