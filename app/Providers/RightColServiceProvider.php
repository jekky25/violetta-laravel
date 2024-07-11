<?php
namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AnketVisit;
use Illuminate\Support\Facades\Session;
use App\Helpers\Helper;

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
		View::composer('*', function($view) {
			$user = Auth::user();
			if (!empty ($user))
			{
				$user = $user->load(['visits']);
				$user->user_lastvisit_format	= Helper::getDate($user->user_lastvisit);
				$user->monthVisits				= count ($user->visits);
				$user->monthVisitsNew			= count (AnketVisit::visitsNew($user));
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
