<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HoroscopeController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\CommentScreenController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\ScreenDownloadController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Top100Controller;
use App\Http\Controllers\ConfirmController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DiaryCommentController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\PhotoDiaryCommentController;
use App\Http\Controllers\PhotoDiaryController;
use App\Http\Controllers\ProfileBrowseController;
use App\Http\Controllers\ProfileFeedController;
use App\Http\Controllers\ProfileSearchController;
use App\Http\Controllers\WebAuthController;

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
	Route::middleware('auth')->group(function () {
		Route::get('registration/edit/diary/', [DiaryController::class, 'myDiaries'])												->name('registration.edit.diary');
		Route::get('registration/edit/settings/', [SettingsController::class, 'index'])											->name('registration.edit.settings');
		Route::put('registration/edit/settings/', [SettingsController::class, 'update'])										->name('registration.edit.settings.post');
		Route::get('registration/edit/', [ProfileController::class, 'editMain'])												->name('registration.edit');
		Route::put('registration/edit/', [ProfileController::class, 'updateMain'])												->name('registration.edit.post');
		Route::get('registration/edit/second/', [ProfileController::class, 'editSecond'])										->name('registration.edit.second');
		Route::put('registration/edit/second/', [ProfileController::class, 'updateSecond'])										->name('registration.edit.second.post');
		Route::get('registration/edit/partner/', [ProfileController::class, 'editPartner'])										->name('registration.edit.partner');
		Route::put('registration/edit/partner/', [ProfileController::class, 'updatePartner'])									->name('registration.edit.partner.post');
		Route::get('registration/edit/photo/', [PhotoController::class, 'index'])												->name('registration.edit.photo');
		Route::post('registration/edit/photo/', [PhotoController::class, 'store'])												->name('registration.edit.photo.post');
		Route::get('registration/edit/photo/{id}.html', [PhotoController::class, 'edit'])					->whereNumber('id')	->name('registration.edit.photo.edit');
		Route::put('registration/edit/photo/{id}.html', [PhotoController::class, 'update'])					->whereNumber('id')	->name('registration.edit.photo.edit.post');
		Route::get('registration/edit/photo/delete/{id}.html', [PhotoController::class, 'destroyPhoto'])	->whereNumber('id')	->name('registration.edit.photo.delete');
		Route::delete('registration/edit/photo/{id}.html', [PhotoController::class, 'destroy'])				->whereNumber('id')->name('registration.edit.photo.delete.action');
		Route::get('registration/edit/pass/', [PasswordController::class, 'show'])												->name('registration.edit.password');
		Route::put('registration/edit/pass/', [PasswordController::class, 'update'])											->name('registration.edit.password.post');
		Route::get('registration/views/', [ProfileFeedController::class, 'views'])												->name('registration.views');

		Route::get('privmsg/', 'PrivmsgController@index')																		->name('privmsg');
		Route::post('privmsg/delete.html', 'PrivmsgController@destroySelected')													->name('privmsg.delete');
		Route::delete('privmsg/delete.html', 'PrivmsgController@destroySelectedAction')											->name('privmsg.delete.action');
		Route::get('privmsg/post/{id}.html', 'PrivmsgController@getAnkMess')							->whereNumber('id')		->name('privmsg.post');
		Route::get('privmsg/post/delete/{id}.html', 'PrivmsgController@destroy')						->whereNumber('id')		->name('privmsg.post.delete');
		Route::delete('privmsg/post/delete/{id}.html', 'PrivmsgController@destroyAction')				->whereNumber('id')		->name('privmsg.post.delete.action');
		Route::post('privmsg/post/add/{id}.html', 'PrivmsgController@store')							->whereNumber('id')		->name('privmsg.post.add');

		Route::get('ank/diary/comment/edit/{id}.html', [DiaryCommentController::class, 'edit'])			->whereNumber('id')		->name('ank.diary.comment.edit.id');
		Route::put('ank/diary/comment/edit/{id}.html', [DiaryCommentController::class, 'update'])		->whereNumber('id')		->name('ank.diary.comment.update.id');
		Route::delete('ank/diary/comment/delete/photo/{id}.html', [PhotoDiaryCommentController::class, 'destroy'])	->whereNumber('id')		->name('ank.diary.comment.delete.photo.id');

		Route::delete('ank/diary/comment/delete/{id}.html', [DiaryCommentController::class, 'destroy'])	->whereNumber('id')		->name('ank.diary.comment.delete.id');
		Route::post('ank/diary/comment/{id}/add.html', [DiaryCommentController::class, 'store'])		->whereNumber('id')		->name('ank.diary.comment.add');
		Route::get('ank/diary/edit/{id}.html', [DiaryController::class, 'edit'])						->whereNumber('id')		->name('ank.diary.edit.id');
		Route::put('ank/diary/edit/{id}.html', [DiaryController::class, 'update'])						->whereNumber('id')		->name('ank.diary.edit.update.id');
		Route::delete('ank/diary/delete/photo/{id}.html', [PhotoDiaryController::class, 'destroy'])		->whereNumber('id')		->name('ank.diary.delete.photo.id');
		Route::delete('ank/diary/delete/{id}.html', [DiaryController::class, 'destroy'])				->whereNumber('id')		->name('ank.diary.delete.id');
		Route::post('ank/diary/add.html', [DiaryController::class, 'store'])													->name('ank.diary.add');
		Route::get('ank/photo/{userId}.html', [PhotoController::class, 'showMain'])						->whereNumber('userId')		->name('ank.photo.id');
		Route::get('ank/f/photo_{id}/', [PhotoController::class, 'show'])								->whereNumber('id')		->name('ank.photo.photo_id');

		Route::get('logout/', [WebAuthController::class, 'logout'])																->name('logout');
	});

	Route::get('registration/top100/', [Top100Controller::class, 'show'])														->name('registration.top100');
	Route::post('registration/top100/', [Top100Controller::class, 'update'])													->name('registration.top100.post');
	Route::delete('registration/delete/', [AccountController::class, 'destroy'])												->name('registration.delete');
	Route::get('registration/', [RegistrationController::class, 'create'])														->name('registration')						->middleWare('guest');
	Route::post('registration/', [RegistrationController::class, 'store'])														->name('registration.post');
	Route::get('registration/confirm/{id}/{code}/', [ConfirmController::class, 'confirm'])				->whereNumber('id')		->name('registration.confirm');

	Route::get('ank/diary/comments/{id}.html', [DiaryCommentController::class, 'index'])								->whereNumber('id')		->name('ank.diary.comments');
	Route::get('ank/diary/{id}.html', [DiaryController::class, 'show'])													->whereNumber('id')		->name('ank.diary.id');
	Route::get('ank/f/{id}/', [ProfileController::class, 'showFull'])													->whereNumber('id')		->name('ank.full.id');
	Route::get('ank/{id}/', [ProfileController::class, 'show'])															->whereNumber('id')		->name('ank.id');

	Route::get('ankets/{sex?}/{age?}', [ProfileBrowseController::class, 'getList'])
    ->where('sex', 'men|women')
    ->where('age', '20|2025|2535|3550|50')
    ->name('ankets');
													 
	Route::get('bestankets/{sex}/',  [ProfileBrowseController::class, 'best'])											->where('sex', '(men|women)')
																																				->name('bestankets.sex');

	Route::get('goroskop/op{id}.html', [HoroscopeController::class, 'showType'])										->whereNumber('id')		->name('horoscope.op');
	Route::get('goroskop/{id}.html', [HoroscopeController::class, 'show'])												->whereNumber('id')		->name('horoscope.id');
	Route::get('goroskop.html', [HoroscopeController::class, 'index'])																			->name('horoscope');

	Route::get('names/{sex}.html', 'NameController@getGender')															->where('sex', '(men|women)')
																																				->name('names.sex');
	Route::get('names/{id}.html', 'NameController@show')																->whereNumber('id')		->name('names.id');
	Route::get('names/{sex}/{id}.html', 'NameController@getGender')														->whereNumber('id')
															   															->where('sex', '(men|women)')
																																				->name('names.subop');
	Route::get('names.html', 'NameController@index')																							->name('names');

	Route::get('population_search/{sex?}/', [ProfileBrowseController::class, 'popular'])								->where('sex', '(men|women)')
																																				->name('population_search');
	Route::get('birthday_search/', [ProfileFeedController::class, 'birthday'])																	->name('birthday_search');

	Route::post('screensaver/download/{id}.html', [ScreenDownloadController::class, 'download'])							->whereNumber('id')		->name('screensavers.id.download');
	Route::post('screensaver/{id}.html', [CommentScreenController::class, 'store'])										->whereNumber('id')		->name('screensavers.id.store');
	Route::get('screensaver/{id}.html', [ScreenController::class, 'show'])												->whereNumber('id')		->name('screensavers.id');
	Route::get('screensavers.html', [ScreenController::class, 'index'])																			->name('screensavers');

	Route::get('dreambook/op{id}.html', 'DreamBookController@index')													->whereNumber('id')		->name('dreambook.literal');
	Route::get('dreambook/{id}.html', 'DreamBookController@show')														->whereNumber('id')		->name('dreambook.id');
	Route::get('dreambook.html', 'DreamBookController@index')																					->name('dreambook');
	Route::get('ank/diaries.html', [DiaryController::class, 'index'])																			->name('diaries');
	Route::get('review/', 'ReviewController@index')																								->name('review');

	Route::get('search/', [ProfileSearchController::class, 'search'])																			->name('search');
	
	Route::post('login/', [WebAuthController::class, 'login'])																					->name('login');
	Route::get('login/', function () {
		return redirect()->route('home');
	});
	Route::post('forget_pass/', [ForgetPasswordController::class, 'sendPassword'])																->name('forget_pass.post');
	Route::get('forget_pass/', [ForgetPasswordController::class, 'show'])																		->name('forget_pass');
	Route::get('sitemap/', 'SiteController@index')																								->name('sitemap');
	Route::get('contacts/', [ContactsController::class, 'index'])																				->name('contacts');
	Route::post('contacts/', [ContactsController::class, 'store'])																				->name('contacts.post');
	Route::get('conditions/', function () 
	{
		return response()->view ('conditions'); 
	})																																			->name('conditions');
});
require_once __DIR__.'/WebRoutes/PhotoComment.php';
/*ajax */
Route::get('/', 'HomeController@index')																											->name('home');

Route::get('forum/', 'ForumController@index')																									->name('forum');
Route::get('forum/topic_{$forum_id}_{$topic_id}.html', 'ForumController@index')																	->name('forum.topic');

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

			Artisan::call('cache:clear');
			Artisan::call('config:cache');
			Artisan::call('view:clear');
			Artisan::call('route:clear');
			return "Сброс кэша выполнен!";
	
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