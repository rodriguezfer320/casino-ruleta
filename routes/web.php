<?php

use App\Http\Controllers\CasinoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas del casino
Route::controller(CasinoController::class)
    ->name('casino.')
    ->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/apuesta', 'storeBet')->name('storeBet');
        Route::put('/ruleta/{round}', 'spinWheel')->name('spinWheel');
        Route::put('/terminar/{round}', 'endRound')->name('endRound');
    });

// Rutas del crud de usuarios
Route::resource('users', UserController::class)
    ->parameters(['users' => 'id'])
    ->except('show');