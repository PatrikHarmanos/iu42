<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TownController;

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

Route::get('/', [
    'uses' => 'App\Http\Controllers\TownController@getIndex',
    'as' => 'town.getIndex'
]);

Route::get('/{id}', [
    'uses' => 'App\Http\Controllers\TownController@getTown',
    'as' => 'town.getTown'
]);
