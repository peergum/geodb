<?php

use Illuminate\Support\Facades\Route;
use Peergum\GeoDB\Console\Commands\GeoDBInstall;
use Peergum\GeoDB\Models\City;
use Peergum\GeoDB\Models\Country;
use Peergum\GeoDB\Models\State;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/geodb', function () {
    return view('geodb::geodb', [
        'countries' => Country::count(),
        'states' => State::count(),
        'cities' => City::count(),
        'version' => GeoDBInstall::VERSION
    ]);
});
