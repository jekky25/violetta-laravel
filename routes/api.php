<?php

use App\Http\Controllers\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ProfileBrowseController;
use App\Http\Controllers\EvaulationController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HomeController;

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
Route::get('/forum/top', [ForumController::class, 'getTop'])->name('forum.get.top');
Route::get('/statistics/', 'StatisticsController@get')->name('statistics.get');
Route::get('/auth/', [ApiAuthController::class, 'me'])->middleware('web')->name('auth.get');
Route::post('/login/', [ApiAuthController::class, 'login'])->middleware('web')->name('login.api');
Route::get('/new_faces/', [HomeController::class, 'newFaces'])->name('newfaces.get');

Route::get('/home/diaries/', [HomeController::class, 'diaries'])->name('home.diaries');

Route::get('/regions/{countryId}/', [RegionController::class, 'index'])->whereNumber('countryId')->name('regionsByCountry');
Route::get('/cities/{regionId}/', [CityController::class, 'index'])->whereNumber('regionId')->name('citiesByCountry');

Route::post('/users/{id}/vote/', [EvaulationController::class, 'store'])->whereNumber('id')->middleware('web')->name('evaulation.store');
