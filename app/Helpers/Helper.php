<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PhotoRepository;

class Helper {
	/**
     * Create a new controller instance.
     *
     * @return void
     */			
    public function __construct(){
    }

	/**
	 * remove the picture from the server and makes update about it in the DB
	 * 
	 * @param App\Models\Photo $photo
	 *
	 * @return bool
	 */
	public static function delPhoto($photo)
	{
		$user 			= Auth::user();
		$id 			= $photo->fotos_id;

		if (file_exists("fotos_new/".$id.".jpg")) {
			if(unlink("fotos_new/".$id.".jpg")) {}
		}

		$isPortret = $photo->fotos_portret == 1 ? 1 : 0;
		$photo->delete();

		if ($isPortret)
		{
			$photo = (new PhotoRepository())->getFirstByUserId($user->user_id);
			if (!empty($photo))
			{
				$photo->fotos_portret = 1;
				$photo->update();
			}
		}

		$user->user_refresh_date 	= date("Y-m-d");
		$user->user_refresh_date_t 	= time();
		$user->user_session_time 	= time();
		$user->user_lastvisit 		= time();
		$user->user_fotos 			= (new PhotoRepository())->getAllByUserId($user->user_id)->count();
		$user->update();

		return true;
	}
}
