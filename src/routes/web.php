<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/geodb', function () {
    return view('geodb::geodb', [
        'countries' => \App\Models\Country::count(),
        'states' => \App\Models\State::count(),
        'cities' => \App\Models\City::count()
    ]);
});
