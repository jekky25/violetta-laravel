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
