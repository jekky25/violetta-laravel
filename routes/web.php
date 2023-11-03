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

Route::get('/', 'HomeController@index')->name('home');



Route::get('/migr', function () {
	//	Artisan::call('make:migration create_users_table');
	//    return "Миграция выполнена!";
});
	
Route::get('/artis', function () {
	//		Artisan::call('make:provider SapeServiceProvider');
			//Artisan::call('make:model Hotel');
			//return "Артисан выполнен!";
});
	
Route::get('/clear', function () {
	/*
			Artisan::call('cache:clear');
			Artisan::call('config:cache');
			Artisan::call('view:clear');
			Artisan::call('route:clear');
			*/
	//		return "Сброс кэша выполнен!";
	
});


if (!function_exists('pr')) {
	function pr (...$ar)
	{
		foreach ($ar as $_ar)
		{
			echo '<pre>'; print_r ($_ar); echo '</pre>';
		}
	}
}