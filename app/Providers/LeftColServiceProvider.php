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
		View::composer('*', function($view) {

			$sql = 'SELECT t.topic_title, t.topic_id, t.forum_id, p.topic_id
			FROM phpbb3_topics t, phpbb3_posts p  
			WHERE t.topic_id = p.topic_id && t.forum_id <> 7 
			GROUP BY t.topic_id, t.topic_title, t.forum_id, p.topic_id ORDER BY p.post_id DESC LIMIT 0 , 5;';
		
			$forums = DB::connection('mysql_for')->select($sql);
			view()->share([
				'statAnkets' 	=> $this->userRepository->getStatistic(),
				'forums'		=> $forums
			]);
		});
    }
}
