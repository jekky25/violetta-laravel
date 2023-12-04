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

Route::get('privmsg', 'NoContoller@index')->name('privmsg');

Route::get('registration/edit/photo', 'NoContoller@index')->name('registration.edit.photo');
Route::get('registration/edit/diary/{id}.html', 'NoContoller@index')->whereNumber('id')->name('registration.edit.diary.id');
Route::get('registration/delete/diary/{id}.html', 'NoContoller@index')->whereNumber('id')->name('registration.delete.diary.id');
Route::get('registration/edit/diary', 'NoContoller@index')->name('registration.edit.diary');
Route::get('registration/edit/settings', 'NoContoller@index')->name('registration.edit.settings');
Route::get('registration/top100', 'NoContoller@index')->name('registration.top100');
Route::get('registration/edit', 'NoContoller@index')->name('registration.edit');
Route::get('registration/views', 'NoContoller@index')->name('registration.views');
Route::get('registration', 'NoContoller@index')->name('registration');

Route::get('privmsg/post/{id}.html', 'NoContoller@index')->whereNumber('id')->name('privmsg.post.id');


Route::get('ank/diary/comments/{id}.html', 'CommentController@index')->whereNumber('id')->name('ank.diary.comments');
Route::get('ank/diary/{id}.html', 'NoContoller@index')->whereNumber('id')->name('ank.diary.id');
Route::get('ank/photo/f/{id}.html', 'NoContoller@index')->whereNumber('id')->name('ank.photo.f.id');
Route::get('ank/photo/{id}.html', 'NoContoller@index')->whereNumber('id')->name('ank.photo.id');
Route::get('ank/f/{id}', 'NoContoller@index')->whereNumber('id')->name('ank.full.id');
Route::get('ank/{id}', 'AnkController@getAnk')->whereNumber('id')->name('ank.id');
Route::get('ankets/{sex}/{age}', 'AnketController@getAnkets')->where('sex', '(men|women)')
                                                     ->where('age', '(20|2025|2535|3550|50)')
                                                     ->name('ankets.sex.age');
Route::get('ankets/{sex}', 'AnketController@getAnkets')->where('sex', '(men|women)')->name('ankets.sex');
Route::get('ankets', 'AnketController@getAnkets')->name('ankets');

Route::get('bestankets/{sex}', 'AnketController@getBestAnkets')->where('sex', '(men|women)')->name('bestankets.sex');

Route::get('goroskop/op{id}.html', 'GoroskopController@getType')->whereNumber('id')->name('goroskop.op');
Route::get('goroskop/{id}.html', 'GoroskopController@getItem')->whereNumber('id')->name('goroskop.id');
Route::get('goroskop.html', 'GoroskopController@index')->name('goroskop');

Route::get('names/{sex}.html', 'NameController@getGender')->where('sex', '(men|women)')->name('names.sex');
Route::get('names/{id}.html', 'NameController@getName')->whereNumber('id')->name('names.id');
Route::get('names/{sex}/{id}.html', 'NameController@getGender')->whereNumber('id')
															   ->where('sex', '(men|women)')->name('names.subop');
Route::get('names.html', 'NameController@index')->name('names');

Route::get('population_search/{sex}/', 'AnketController@getPopularAnkets')->where('sex', '(men|women)')->name('population_search.sex');
Route::get('population_search', 'AnketController@getPopularAnkets')->name('population_search');
Route::get('birthday_search', 'AnketController@getBirthdayAnkets')->name('birthday_search');

Route::match(['get', 'post'], 'screensaver/{id}.html', 'ScreenController@getItem')->whereNumber('id')->name('screensavers.id');
Route::get('screensavers.html', 'ScreenController@index')->name('screensavers');

Route::get('dreambook/op{id}.html', 'DreamBookController@index')->whereNumber('id')->name('dreambook.literal');
Route::get('dreambook/{id}.html', 'DreamBookController@getItem')->whereNumber('id')->name('dreambook.id');
Route::get('dreambook.html', 'DreamBookController@index')->name('dreambook');
Route::get('ank/diaries.html', 'DiaryController@index')->name('diaries');
Route::get('review', 'NoContoller@index')->name('review');

Route::get('search', 'AnketController@getBySearch')->name('search');



Route::get('logout', 'NoContoller@index')->name('logout');
Route::get('login', 'NoContoller@index')->name('login');
Route::get('forget_pass', 'NoContoller@index')->name('forget_pass');
Route::get('sitemap', 'NoContoller@index')->name('sitemap');
Route::get('contacts', 'NoContoller@index')->name('contacts');

Route::match(['get', 'post'], 'login.html', 'NoContoller@index')->name('login');

/*ajax */
Route::get('ajax/geo.php', 'ajaxController@getGeo');


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


Route::get('/test', function () {

	//Session::put('key', 'value32444');
	//Auth::loginUsingId(68420, true);
	//Auth::loginUsingId(1, true);
	//Auth::logout();
	return "Тест!";
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