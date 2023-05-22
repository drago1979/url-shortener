<?php

use App\Http\Controllers\Url\UrlController;
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

Route::controller(UrlController::class)->group(function () {
    Route::get('/', 'create')->name('home');
    Route::post('/', 'store')->name('url_store');
    Route::get('/urls/{url}', 'show')->name('url_show');
    Route::get('/{value}', 'redirect');
});
