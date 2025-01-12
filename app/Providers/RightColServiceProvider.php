<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Services\DataService;
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
	public function boot(DataService $data)
	{
		$this->anketVisitRepository	= new AnketVisitRepository();

		View::composer('*', function($view) use ($data) {
			$user = Auth::user();
			if (!empty ($user))
			{
				$user = $user->load(['visits']);
				$user->user_lastvisit_format	= $data->getDate($user->user_lastvisit);
				$user->monthVisits				= count ($user->visits);
				$user->monthVisitsNew			= count ($this->anketVisitRepository->visitsNew($user));
			}

            $view->with(['user' => $user]);
			$copyright = '© 2006-' . date("Y",time()) . ' Сайт знакомств Виолетта';

			view()->share([
				'copyright'	=> $copyright
			]);
        });
    }
}
