<?php

use App\Events\OrderShipmentStatusUpdated;
use App\Models\User;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/swagger', function () {
    return view('swagger');
});

Route::get('/test', function () {
    OrderShipmentStatusUpdated::dispatch('11');
});

Route::get('/listen', function () {
    return view('listen');
});

Route::get('e', function () {
    dd(User::search('example.com')->count());
});
