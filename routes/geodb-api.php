<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Peergum\GeoDB\Http\Controllers\CityController;
use Peergum\GeoDB\Http\Controllers\CountryController;
use Peergum\GeoDB\Http\Controllers\StateController;

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

Route::middleware('api')->prefix('api')->group(function () {
    Route::resource('countries', CountryController::class)->only('index', 'show');
    Route::resource('countries.states', StateController::class)->only('index', 'show');
    Route::resource('cities', CityController::class)->only('index', 'show');
});
