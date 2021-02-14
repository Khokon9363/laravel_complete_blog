<?php

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

Auth::routes();

Route::middleware(['admin.authority'])->group(function () {
    Route::get('/admin/dashboard', 'HomeController@admin');
});

Route::middleware(['operator.authority'])->group(function () {
    Route::get('/operator/dashboard', 'HomeController@operator');
});

Route::middleware(['user.authority'])->group(function () {
    Route::get('/user/dashboard', 'HomeController@user');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/posts', 'PostController@posts');
    Route::post('/store/post', 'PostController@store');
});