<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class MetaServiceProvider extends ServiceProvider
{
	/**
	* Register services.
	*
	* @return void
	*/
	public function register()
	{
        //
	}

	/**
	* Bootstrap services.
	*
	* @return void
	*/
	public function boot()
	{
		View::composer('*', function($view) {
			$routeName = Route::currentRouteName();
			switch ($routeName) {
				case 'goroskop':
					$titleId = 'Зодиак';
					$pageTitle = $titleId . ', Гороскопы, бесплатные знакомства, Бесплатный сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="' . $titleId . '. Узнай свой гороскоп на сайте знакомств Виолетта.">
						<meta name="Keywords" content="' . $titleId . ', гороскопы, бесплатные знакомства, знакомства в Москве, поиск анкет, найти любовь">';
					break;
				case 'goroskop.id':
					$goroskop 		= $view->goroskop;
					$pageTitle 		= $goroskop->gor_name . ', Гороскопы, бесплатные знакомства, Бесплатный сайт знакомств Виолетта';
					$pageMeta 		= '<meta name="Description" content="' . $goroskop->gor_name . '. Узнай свой гороскоп на сайте знакомств Виолетта.">
							<meta name="Keywords" content="' . $goroskop->gor_name . ', гороскопы, бесплатные знакомства, знакомства в Москве, поиск анкет, найти любовь">';
					break;
				case 'names':
					$pageTitle 		= 'Значение имени, Что означает ваше имя, Бесплатный сайт знакомств Виолетта';
					$pageMeta 		= '<meta name="Description" content="Значение имени. Узнайте, что означает ваше имя. А также знакомства и многое другое.">
								<meta name="Keywords" content="значение имени, что означает имя, знакомства в Москве, поиск анкет, найти любовь">';
					break;
				case 'names.sex':
					$sex = $view->sex;
					$pageTitle 		= $sex == 'men' ? 'Значение мужского имени, Что означает мужское имя, Бесплатный сайт знакомств Виолетта' :
													  'Значение женского имени, Что означает женское имя, Бесплатный сайт знакомств Виолетта';
					$pageMeta 		= $sex == 'men' ? '<meta name="Description" content="Значение мужского имени.  что означает мужское имя. А также знакомства и многое другое.">
													<meta name="Keywords" content="значение мужского имени, что мужское означает имя, знакомства в Москве, поиск анкет, найти любовь">' :
													'<meta name="Description" content="Значение женского имени.  что означает женское имя. А также знакомства и многое другое.">
													<meta name="Keywords" content="значение женского имени, что женское означает имя, знакомства в Москве, поиск анкет, найти любовь">';
					break;
				case 'names.id':
					$name = $view->name;
					$pageTitle = 'Значение имени ' . $name->name . ', Что означает имя ' . $name->name . ', Бесплатный сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="Значение имени ' . $name->name . '.  что означает имя ' . $name->name . '. А также знакомства и многое другое.">
					<meta name="Keywords" content="значение имени ' . $name->name . ', что означает имя ' . $name->name . ', знакомства в Москве, поиск анкет, найти любовь">';
					break;
				case 'population_search':
				case 'population_search.sex':
					$sex = $view->sex;
					$page = $view->page;
					$str 		= $sex == 'men' ? 'Самые популярные мужчины' : 'Самые популярные женщины';
					$pageOut 	= $page > 1 	? ' страница ' . $page : '';
					$pageTitle 	= $str . ', Самые популярные участники, Сайт знакомств Виолетта' . $pageOut;
					$pageMeta 	= '<meta name="Description" content="' . $str . '. Самые популярные пользователи на сайте знакомств.">
									<meta name="Keywords" content="' . $str . ', самые популярные пользователи на сайте знакомств, сайт знакомств, бесплатные знакомства">';
					break;
				case 'birthday_search':
					$page = $view->page;
					$pageOut 	= $page > 1 	? ' страница ' . $page : '';
					$pageTitle = 'Участники отмечающие день рождения, Сайт знакомств Виолетта' . $pageOut;
					$pageMeta = '<meta name="Description" content="Участники отмечающие день рождения на сайт знакомств. ПОЗДРАВЛЯЕМ. ">
								<meta name="Keywords" content="Участники отмечающие день рождения на сайт знакомств, ПОЗДРАВЛЯЕМ, сайт знакомств, бесплатные знакомства">';
					break;

				case 'screensavers':	
					$page = $view->page;
					$pageOut 	= $page > 1 ? ' страница ' . $page : '';
					$pageTitle = 'Хранители экрана, Скринсейверы, Screensaver, Скачать заставку, Сайт знакомств Виолетта' . $pageOut;
					$pageMeta = '<meta name="Description" content="Хранители экрана. Большая коллекция экранных заставок на водную тему. Можно скачать прямо сейчас.">
							<meta name="Keywords" content="Хранители экрана, Скринсейверы, Screensaver, Скачать заставку, Сайт знакомств Виолетта">';
					break;
				case 'screensavers.id':	
					$page = $view->page;
					$screen = $view->screen;
					$pageOut 	= $page > 1 ? ' страница ' . $page : '';
					$pageTitle 	= $screen->name . ', Хранитель экрана, Скринсейвер, Screensaver, Скачать заставку с сайта знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="' . $screen->name . ' - скачай хранитель экрана прямо сейчас. Большая коллекция экранных заставок на водную тему.">
					<meta name="Keywords" content="' . $screen->name . ', Хранители экрана, Скринсейверы, Screensaver, Скачать заставку, Сайт знакомств Виолетта">';
					break;

				case 'dreambook':	
					$pageTitle = 'Сонник, Что тебе снится, Толкование Снов, Бесплатный сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="Сонник. Толкователь снов.">
					<meta name="Keywords" content="сонник толкователь снов сновидения">';
					break;

				case 'dreambook.id':
					$dreambook = $view->dreambook;
					$pageTitle = 'Толкование снов: ' . $dreambook->name . ', Сонник, Сновидения, Сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="Толкование снов: ' . $dreambook->name . ', сонник  ' . $dreambook->name . '. Сновидения.">
						<meta name="Keywords" content="сонник ' . $dreambook->name . ' толкование снов сновидения">';
					break;

				case 'ankets.sex.age':
				case 'ankets.sex':
					$ankTitleId = $view->ankTitleId;
					$pageTitle = $ankTitleId . ' на сайте знакомств, сайт знакомств, Бесплатный сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="' . $ankTitleId . ' на сайте знакомств. Тысячи анкет с фото, бесплатная регистрация.">
								<meta name="Keywords" content="' . $ankTitleId . ' на сайте знакомств, бесплатные знакомства, знакомства в Москве, найти любовь">';
					break;

				case 'search':
					$photo = $view->photo;

					$pageTitle = 'Поиск анкет, Сайт знакомств, Бесплатный сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="Поиск анкет на сайте знакомств. Найди свою любовь. Тысячи анкет с фото, бесплатная регистрация.">
						<meta name="Keywords" content="поиск анкет, сайт знакомств, бесплатные знакомства, знакомства в Москве, найти любовь">';

					if (!empty($photo))
					{
						$pageTitle = 'Поиск анкет с фото, Сайт знакомств, Бесплатный сайт знакомств Виолетта';
						$pageMeta = '<meta name="Description" content="Поиск анкет с фотографиями. Тысячи анкет с фото, бесплатная регистрация.">
		    				<meta name="Keywords" content="поиск анкет с фото, анкеты с фотографиями, сайт знакомств, бесплатные знакомства, знакомства в Москве">';
					}
					break;

				case 'ankets':
					$pageTitle = 'Поиск анкет на сайте знакомств, сайт знакомств, Бесплатный сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="Поиск анкет на сайте знакомств. Тысячи анкет с фото, бесплатная регистрация.">
						<meta name="Keywords" content="поиск анкет на сайте знакомств, бесплатные знакомства, знакомства в Москве, найти любовь">';
					break;

				case 'diaries':
					$pageTitle = 'Дневники, скачать дневник на сайте знакомств, бесплатные знакомства';
					$pageTitle .= ', Сайт знакомств Виолетта';	
					$pageMeta = '<meta name="Description" content="Дневник, скачать дневник на сайте знакомств, бесплатные знакомства">
						<meta name="Keywords" content="Дневник, сайт знакомств">';					
					break;

				case 'ank.id':
					$userData = $view->userData;
					$pageTitle = 'Бесплатные знакомства ' . $userData->city_name . ', ' . $userData->user_name . ' ' . $userData->user_age_str . ', ' . $userData->city->name;
					$pageTitle .= ', Сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="Бесплатные знакомства ' . $userData->user_name . ' ' . $userData->user_age_str . ', ' . $userData->city->name . '">
					<meta name="Keywords" content="' . $userData->user_name . ' ' . $userData->user_age_str . ', ' . $userData->city->name . ', сайт знакомств">';
					break;

				case 'ank.diary.id':
					$userData = $view->userData;
					$pageTitle = 'Дневник, бесплатные знакомства, ' . $userData->user_name . ' ' . $userData->user_age_str . ', ' . $userData->city->name;
					$pageTitle .= ', Сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="Дневник, бесплатные знакомства ' . $userData->city->name . ', ' . $userData->user_name . ' ' . $userData->user_age_str . ', ' . $userData->city->name . '">
<meta name="Keywords" content="' . $userData->user_name . ' ' . $userData->user_age_str . ', ' . $userData->city->name . ', сайт знакомств">';
					break;

				case 'ank.diary.comments':
					$userData = $view->diary->user;
					$pageTitle = 'Комментарии к дневнику, бесплатные знакомства, ' . $userData->city->name . ', ' . $userData->user_name . ' ' . $userData->user_age_str;
					$pageTitle .= ', Сайт знакомств Виолетта';

					$pageMeta = '<meta name="Description" content="Дневник, бесплатные знакомства ' . $userData->city->name . ', ' . $userData->user_name . ' ' . $userData->user_age_str . '">
						<meta name="Keywords" content="' . $userData->city->name . ', ' . $userData->user_age_str . ', ' . $userData->user_name . ', сайт знакомств">';
					break;

				case 'privmsg':
				case 'privmsg.post':
					$pageTitle = 'Мои сообщения, бесплатные знакомства, Сайт знакомств Виолетта';
					$pageMeta = '<meta name="Description" content="Мои сообщения, бесплатные знакомства">
								<meta name="Keywords" content="сайт знакомств">';
					break;

				default:
				$pageTitle 	= !empty ($pageTitle)  	? $pageTitle 	: 'Сайт знакомств, бесплатные знакомства, Бесплатный сайт знакомств Виолетта, знакомства бесплатно, клуб знакомств, интернет знакомства и общение';
				$pageMeta 	= !empty ($pageMeta) 	? $pageMeta 	: '<!-- toodoo-key: Aed2rQWEJC9rAnmJG5cwh -->
			<meta name="Description" content="Сайт знакомств на любой вкус. Найди свою любовь. Тысячи анкет с фото, бесплатная регистрация.">
			<meta name="Keywords" content="сайт знакомств, бесплатные знакомства, знакомства в Москве, поиск анкет, найти любовь, знакомства бесплатно, служба знакомств, клуб знакомств, чат знакомств, знакомства с девушками, знакомства с мужчинами, интернет знакомства и общение">';
			}
            $view->with(['title' => $pageTitle, 'pageMeta' => $pageMeta]);
        });
    }
}
