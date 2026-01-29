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

Route::middleware(['slashes', 'auth'])->group(function () {
	Route::post('ank/photo/{id}.html', 'PhotoCommentController@store')->whereNumber('id')->name('ank.photo.id.post.comment');
});
