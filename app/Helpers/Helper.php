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
	 * select a separate year, month or day from the date
	 * 
	 * @param string $date
	 * @param integer $mode
	 *
	 * @return array
	 */
	public static function selectFromDate($date,$mode)
	{
  		preg_match("/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/",$date,$pockets_old);
  		return $pockets_old[$mode];
	}

	/**
	 * transform day, month and year to data string format 0000-00-00
	 * 
	 * @param integer $day
	 * @param integer $month
	 * @param integer $year
	 *
	 * @return string
	 */
	public static function getDateStr($day,$month,$year)
	{
		$day 	= $day < 10 	? "0$day" 	: $day;
		$month 	= $month < 10 	? "0$month" : $month;
		return "$year-$month-$day";
	}

	/**
	 * serialize array from the input fields
	 * 
	 * @param array $data
	 *
	 * @return string
	 */
	public static function serializeInput($data)
	{
		return !is_array($data) ? '' : serialize ($data);
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
