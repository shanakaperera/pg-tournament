<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PkgUsersController;
use App\Http\Controllers\TournamentsController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Pkg Users

Route::get('pkg-users', [PkgUsersController::class, 'users'])
    ->name('pkg-users')
    ->middleware('auth');

Route::get('pkg-users/{email}/get', [PkgUsersController::class, 'show'])
    ->name('pkg-users.show')
    ->middleware('auth');

// Tournaments

Route::get('tournaments', [TournamentsController::class, 'index'])
    ->name('tournaments')
    ->middleware('auth');

Route::get('tournaments/create', [TournamentsController::class, 'create'])
    ->name('tournaments.create')
    ->middleware('auth');

Route::post('tournaments', [TournamentsController::class, 'store'])
    ->name('tournaments.store')
    ->middleware('auth');

Route::get('tournaments/{tournament}/edit', [TournamentsController::class, 'edit'])
    ->name('tournaments.edit')
    ->middleware('auth');

Route::put('tournaments/{tournament}', [TournamentsController::class, 'update'])
    ->name('tournaments.update')
    ->middleware('auth');

Route::delete('tournaments/{tournament}', [TournamentsController::class, 'destroy'])
    ->name('tournaments.destroy')
    ->middleware('auth');

Route::get('pkg-series', [TournamentsController::class, 'series'])
    ->name('pkg-series')
    ->middleware('auth');

Route::get('pkg-structures', [TournamentsController::class, 'structures'])
    ->name('pkg-structures')
    ->middleware('auth');

Route::get('pkg-games', [TournamentsController::class, 'games'])
    ->name('pkg-games')
    ->middleware('auth');

Route::get('pkg-limits', [TournamentsController::class, 'limits'])
    ->name('pkg-limits')
    ->middleware('auth');

Route::get('pkg-prizes', [TournamentsController::class, 'prizes'])
    ->name('pkg-prizes')
    ->middleware('auth');

Route::get('pkg-grades', [TournamentsController::class, 'grades'])
    ->name('pkg-grades')
    ->middleware('auth');

Route::get('pkg-tournaments', [TournamentsController::class, 'tournaments'])
    ->name('pkg-tournaments')
    ->middleware('auth');
