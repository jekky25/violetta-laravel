<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Repositories\PhotoRepository;
use App\Repositories\UserRepository;

class LeftColServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->photoRepository 		= new PhotoRepository();
		$this->userRepository 		= new UserRepository();
		View::composer('*', function ($view) {
			view()->share([
				'statAnkets' 	=> $this->userRepository->getStatistic()
			]);
		});
	}
}
