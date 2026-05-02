<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ProfileBrowseController;
use App\Http\Controllers\EvaulationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/top100/{sex}', [ProfileBrowseController::class, 'top100'])->whereNumber('sex')->name('profile.get.top100');
Route::get('/forum/top', 'ForumController@getTop')->name('forum.get.top');
Route::get('/statistics/', 'StatisticsController@get')->name('statistics.get');
Route::get('/auth/', 'AuthController@getAuth')->name('auth.get');
Route::post('/login/', 'AuthController@loginApi')->name('login.api');
Route::get('/new_faces/', 'HomeController@newFaces')->name('newfaces.get');

Route::get('/home/diaries/', 'HomeController@diaries')->name('home.diaries');

Route::get('/regions/{countryId}/', [RegionController::class, 'index'])->whereNumber('countryId')->name('regionsByCountry');
Route::get('/cities/{regionId}/', [CityController::class, 'index'])->whereNumber('regionId')->name('citiesByCountry');

Route::post('/users/{id}/vote/', [EvaulationController::class, 'store'])->whereNumber('id')->middleware('web')->name('evaulation.store');
