<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\Helper;
use App\Repositories\AnketVisitRepository;
use App\Repositories\UserRepository;

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
		$this->anketVisitRepository	= new AnketVisitRepository();
		$this->userRepository 		= new UserRepository();

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

		$wItem = $this->userRepository->getTop100(WOMEN, 1);
		$mItem = $this->userRepository->getTop100(MEN, 1);

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
