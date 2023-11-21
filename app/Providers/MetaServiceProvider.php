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
