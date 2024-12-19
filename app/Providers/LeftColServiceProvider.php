<?php
namespace App\Providers;

use Illuminate\Support\Facades\DB;
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
	public function register()
	{

	}

	/**
	* Bootstrap services.
	*
	* @return void
	*/
	public function boot()
	{
		$this->photoRepository 		= new PhotoRepository();
		$this->userRepository 		= new UserRepository();
		$statAnkets = [];
		$statAnkets['total_women'] 			= $this->userRepository->getCountAnkets(WOMEN);
		$statAnkets['total_men'] 			= $this->userRepository->getCountAnkets(MEN);
		$statAnkets['total_fotos'] 			= $this->photoRepository->getCount();

		$statAnkets['total_ankets'] 		= $statAnkets['total_men'] + $statAnkets['total_women'];
		$statAnkets['women_ank_percent'] 	= round(( $statAnkets['total_women'] / $statAnkets['total_ankets'] ) * 100);
		$statAnkets['women_ank_percent_l'] 	= sprintf('%d%%', $statAnkets['women_ank_percent']);
		$statAnkets['men_ank_percent'] 		= round(( $statAnkets['total_men'] / $statAnkets['total_ankets'] ) * 100);
		$statAnkets['men_ank_percent_l'] 	= sprintf('%d%%', $statAnkets['men_ank_percent']);
		$statAnkets['total_men_percent'] 	= $statAnkets['men_ank_percent_l'];
		$statAnkets['total_women_percent'] 	= $statAnkets['women_ank_percent_l'];
	
		$sql = 'SELECT t.topic_title, t.topic_id, t.forum_id, p.topic_id
		FROM phpbb3_topics t, phpbb3_posts p  
		WHERE t.topic_id = p.topic_id && t.forum_id <> 7 
		GROUP BY t.topic_id, t.topic_title, t.forum_id, p.topic_id ORDER BY p.post_id DESC LIMIT 0 , 5;';
	
		$forums = DB::connection('mysql_for')->select($sql);

		view()->share([
			'statAnkets' 	=> $statAnkets,
			'forums'		=> $forums
		]);
    }
}
