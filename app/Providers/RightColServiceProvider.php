<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;

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
		$wItem = User::getTop100(WOMEN, 1);
		$mItem = User::getTop100(MEN, 1);

		$top100 = [
			'women' => $wItem,
			'men' 	=> $mItem,
		];

		view()->share(['top100' => $top100]);
    }
}
