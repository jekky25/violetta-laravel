<?php
namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Photo;

class LeftColServiceProvider extends ServiceProvider
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
		$statAnkets = [];
		$statAnkets['total_women'] 			= User::getCountAnkets(WOMEN);
		$statAnkets['total_men'] 			= User::getCountAnkets(MEN);
		$statAnkets['total_fotos'] 			= Photo::getCountPhotos();

		$statAnkets['total_ankets'] 		= $statAnkets['total_men'] + $statAnkets['total_women'];
		$statAnkets['women_ank_percent'] 	= round(( $statAnkets['total_women'] / $statAnkets['total_ankets'] ) * 100);
		$statAnkets['women_ank_percent_l'] 	= sprintf('%d%%', $statAnkets['women_ank_percent']);
		$statAnkets['men_ank_percent'] 		= round(( $statAnkets['total_men'] / $statAnkets['total_ankets'] ) * 100);
		$statAnkets['men_ank_percent_l'] 	= sprintf('%d%%', $statAnkets['men_ank_percent']);
		$statAnkets['total_men_percent'] 	= $statAnkets['men_ank_percent_l'];
		$statAnkets['total_women_percent'] 	= $statAnkets['women_ank_percent_l'];
	
		view()->share([
			'statAnkets' 	=> $statAnkets,
		]);
    }
}
