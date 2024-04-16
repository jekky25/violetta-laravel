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



Route::middleware('slashes')->group(function () {
	Route::get('registration/edit/photo/', 'NoContoller1@index')																				->name('registration.edit.photo');
	Route::get('registration/edit/diary/{id}.html', 'NoContoller2@index')												->whereNumber('id')		->name('registration.edit.diary.id');
	Route::get('registration/delete/diary/{id}.html', 'NoContoller3@index')												->whereNumber('id')		->name('registration.delete.diary.id');
	Route::get('registration/edit/diary/', 'RegistrationController@diary')																		->name('registration.edit.diary')			->middleWare('auth');
	Route::get('registration/edit/settings/', 'RegistrationController@settings')																->name('registration.edit.settings')		->middleWare('auth');
	Route::post('registration/edit/settings/', 'RegistrationController@settingsPost')															->name('registration.edit.settings.post')	->middleWare('auth');
	Route::get('registration/top100/', 'RegistrationController@top100')																			->name('registration.top100');
	Route::post('registration/top100/', 'RegistrationController@top100Post')																	->name('registration.top100.post');
	Route::get('registration/edit/', 'RegistrationController@edit')																				->name('registration.edit')					->middleWare('auth');
	Route::post('registration/edit/', 'RegistrationController@editPost')																		->name('registration.edit.post')			->middleWare('auth');
	Route::get('registration/edit/second/', 'RegistrationController@second')																	->name('registration.edit.second')			->middleWare('auth');
	Route::post('registration/edit/second/', 'RegistrationController@secondPost')																->name('registration.edit.second.post')		->middleWare('auth');
	Route::get('registration/edit/partner/', 'RegistrationController@partner')																	->name('registration.edit.partner')			->middleWare('auth');
	Route::post('registration/edit/partner/', 'RegistrationController@partnerPost')																->name('registration.edit.partner.post')	->middleWare('auth');
	Route::get('registration/edit/photo/', 'RegistrationController@photo')																		->name('registration.edit.photo')			->middleWare('auth');
	Route::post('registration/edit/photo/', 'RegistrationController@photoPost')																	->name('registration.edit.photo.post')		->middleWare('auth');
	Route::get('registration/edit/photo/edit/{id}.html', 'RegistrationController@editPhoto')							->whereNumber('id')		->name('registration.edit.photo.edit')		->middleWare('auth');
	Route::post('registration/edit/photo/edit/{id}.html', 'RegistrationController@editPhotoPost')						->whereNumber('id')		->name('registration.edit.photo.edit.post')	->middleWare('auth');
	Route::match(['get', 'post'], 'registration/edit/photo/delete/{id}.html', 'RegistrationController@deletePhoto')		->whereNumber('id')		->name('registration.edit.photo.delete')	->middleWare('auth');
	Route::get('registration/edit/pass/', 'RegistrationController@pass')																		->name('registration.edit.password')		->middleWare('auth');
	Route::post('registration/edit/pass/', 'RegistrationController@passPost')																	->name('registration.edit.password.post')	->middleWare('auth');
	Route::get('registration/delete/', 'RegistrationController@delete')																			->name('registration.delete')				->middleWare('auth');
	Route::get('registration/delete/confirm/', 'RegistrationController@deleteConfirm')															->name('registration.delete.confirm')		->middleWare('auth');
	Route::get('registration/views/', 'NoContoller8@index')																						->name('registration.views');
	Route::get('registration/', 'RegistrationController@registration')																			->name('registration');
	Route::post('registration/', 'RegistrationController@registrationPost')																		->name('registration.post');
	Route::get('registration/confirm/{id}/{code}/', 'RegistrationController@confirm')									->whereNumber('id')		->name('registration.confirm');

	Route::get('privmsg/', 'PrivmsgController@index')																							->name('privmsg')							->middleWare('auth');
	Route::post('privmsg/delete.html', 'PrivmsgController@delete')																				->name('privmsg.delete')					->middleWare('auth');
	Route::get('privmsg/post/{id}.html', 'PrivmsgController@getAnkMess')												->whereNumber('id')		->name('privmsg.post')						->middleWare('auth');
	Route::match(['get', 'post'], 'privmsg/post/delete/{id}.html', 'PrivmsgController@deletePost')						->whereNumber('id')		->name('privmsg.post.delete')				->middleWare('auth');
	Route::post('privmsg/post/add/{id}.html', 'PrivmsgController@addPost')												->whereNumber('id')		->name('privmsg.post.add');

	Route::get('ank/diary/comments/{id}.html', 'AnkController@getDiaryComments')										->whereNumber('id')		->name('ank.diary.comments');
	Route::match(['get', 'post'], 'ank/diary/edit/{id}.html', 'AnkController@editDiary')								->whereNumber('id')		->name('ank.diary.edit.id')					->middleWare('auth');
	Route::match(['get', 'post'], 'ank/diary/delete/photo/{id}.html', 'AnkController@delDiaryPhoto')					->whereNumber('id')		->name('ank.diary.delete.photo.id')			->middleWare('auth');
	Route::match(['get', 'post'], 'ank/diary/delete/{id}.html', 'AnkController@delDiary')								->whereNumber('id')		->name('ank.diary.delete.id')				->middleWare('auth');
	Route::match(['get', 'post'], 'ank/diary/comment/edit/{id}.html', 'AnkController@editDiaryComment')					->whereNumber('id')		->name('ank.diary.comment.edit.id')			->middleWare('auth');
	Route::match(['get', 'post'], 'ank/diary/comment/delete/{id}.html', 'AnkController@delDiaryComment')				->whereNumber('id')		->name('ank.diary.comment.delete.id')		->middleWare('auth');
	Route::match(['get', 'post'], 'ank/diary/comment/delete/photo/{id}.html', 'AnkController@delDiaryCommentPhoto')		->whereNumber('id')		->name('ank.diary.comment.delete.photo.id')	->middleWare('auth');
	Route::get('ank/diary/{id}.html', 'AnkController@getDiary')															->whereNumber('id')		->name('ank.diary.id');
	Route::post('ank/diary/add.html', 'AnkController@addDiary')																					->name('ank.diary.add')						->middleWare('auth');
	Route::post('ank/diary/comment/{id}/add.html', 'AnkController@addDiaryComment')										->whereNumber('id')		->name('ank.diary.comment.add')				->middleWare('auth');
	Route::get('ank/photo/f/{id}.html', 'NoContoller12@index')															->whereNumber('id')		->name('ank.photo.f.id');
	Route::get('ank/photo/{id}.html', 'AnkController@getPhoto')															->whereNumber('id')		->name('ank.photo.id')						->middleWare('auth');
	Route::post('ank/photo/{id}.html', 'AnkController@postComment')														->whereNumber('id')		->name('ank.photo.id.post.comment')			->middleWare('auth');
	Route::get('ank/f/photo_{id}/', 'AnkController@getPhoto')															->whereNumber('id')		->name('ank.photo.photo_id');
	Route::get('ank/f/{id}/', 'AnkController@getAnk')																	->whereNumber('id')		->name('ank.full.id');
	Route::get('ank/{id}/', 'AnkController@getAnk')																		->whereNumber('id')		->name('ank.id');
	Route::get('ankets/{sex}/{age}/', 'AnketController@getAnkets')														->where('sex', '(men|women)')
                                                     																	->where('age', '(20|2025|2535|3550|50)')
																																				->name('ankets.sex.age');

	Route::get('ankets/{sex}/', 'AnketController@getAnkets')															->where('sex', '(men|women)')
																																				->name('ankets.sex');
	Route::get('ankets/', 'AnketController@getAnkets')																							->name('ankets');
													 
	Route::get('bestankets/{sex}/', 'AnketController@getBestAnkets')													->where('sex', '(men|women)')
																																				->name('bestankets.sex');

	Route::get('goroskop/op{id}.html', 'GoroskopController@getType')													->whereNumber('id')		->name('goroskop.op');
	Route::get('goroskop/{id}.html', 'GoroskopController@getItem')														->whereNumber('id')		->name('goroskop.id');
	Route::get('goroskop.html', 'GoroskopController@index')																						->name('goroskop');

	Route::get('names/{sex}.html', 'NameController@getGender')															->where('sex', '(men|women)')
																																				->name('names.sex');
	Route::get('names/{id}.html', 'NameController@getName')																->whereNumber('id')		->name('names.id');
	Route::get('names/{sex}/{id}.html', 'NameController@getGender')														->whereNumber('id')
															   															->where('sex', '(men|women)')
																																				->name('names.subop');
	Route::get('names.html', 'NameController@index')																							->name('names');

	Route::get('population_search/{sex}/', 'AnketController@getPopularAnkets')											->where('sex', '(men|women)')
																																				->name('population_search.sex');
	Route::get('population_search/', 'AnketController@getPopularAnkets')																		->name('population_search');
	Route::get('birthday_search/', 'AnketController@getBirthdayAnkets')																			->name('birthday_search');

	Route::match(['get', 'post'], 'screensaver/{id}.html', 'ScreenController@getItem')									->whereNumber('id')		->name('screensavers.id');
	Route::get('screensavers.html', 'ScreenController@index')																					->name('screensavers');

	Route::get('dreambook/op{id}.html', 'DreamBookController@index')													->whereNumber('id')		->name('dreambook.literal');
	Route::get('dreambook/{id}.html', 'DreamBookController@getItem')													->whereNumber('id')		->name('dreambook.id');
	Route::get('dreambook.html', 'DreamBookController@index')																					->name('dreambook');
	Route::get('ank/diaries.html', 'DiaryController@index')																						->name('diaries');
	Route::get('review/', 'NoContoller@index')																									->name('review');

	Route::get('search/', 'AnketController@getBySearch')																						->name('search');
	
	Route::get('logout/', 'RegistrationController@logout')																						->name('logout')->middleWare('auth');
	Route::post('login/', 'RegistrationController@login')																						->name('login');
	Route::get('login/', function () {
		return redirect()->route('home');
	});
	Route::post('forget_pass/', 'RegistrationController@forgetPassPost')																		->name('forget_pass.post');
	Route::get('forget_pass/', 'RegistrationController@forgetPass')																				->name('forget_pass');
	Route::get('sitemap/', 'NoContoller@index')																									->name('sitemap');
	Route::get('contacts/', 'NoContoller@index')																								->name('contacts');
	Route::get('conditions/', function () 
	{	
		return response()->view ('conditions'); 
	})																																			->name('conditions');
});

/*ajax */
Route::get('ajax/geo.php', 'ajaxController@getGeo');
Route::get('/', 'HomeController@index')																											->name('home');



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