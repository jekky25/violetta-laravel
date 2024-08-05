<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\Helper;
use App\Repositories\AnketVisitRepository;

class RightColServiceProvider extends ServiceProvider
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
		$this->anketVisitRepository = new AnketVisitRepository();

		View::composer('*', function($view) {
			$user = Auth::user();
			if (!empty ($user))
			{
				$user = $user->load(['visits']);
				$user->user_lastvisit_format	= Helper::getDate($user->user_lastvisit);
				$user->monthVisits				= count ($user->visits);
				$user->monthVisitsNew			= count ($this->anketVisitRepository->visitsNew($user));
			}

            $view->with(['user' => $user]);
        });

		$wItem = User::getTop100(WOMEN, 1);
		$mItem = User::getTop100(MEN, 1);

		$top100 = [
			'women' => $wItem,
			'men' 	=> $mItem,
		];

		$copyright = '© 2006-' . date("Y",time()) . ' Сайт знакомств Виолетта';

		view()->share([
			'top100' 	=> $top100,
			'copyright'	=> $copyright,
		]);
    }
}
