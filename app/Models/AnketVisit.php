<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AnketVisit extends Model
{
	use HasFactory;

	public $timestamps 	= false;
	protected $table = 'anket_visit';
	protected $fillable = [
		'ank_user_id',
		'user_id_prosm',
		'ank_time'
	];

	/**
    * get user visits over userId
    * @param  int  $int
	* @param  int  $days
	* @param  int  $userId
    * @return \Illuminate\Database\Eloquent\Collection 
    */
	public static function getVisitsByUserId($id, $days, $userId = 0)
	{
		$time = \Carbon\Carbon::now()->subDays($days)->toArray();
		$items = self::select('*')
		->where('user_id_prosm', $id)
		->where('ank_time', '>', $time['timestamp']);

		if ($userId > 0)
			$items->where('ank_user_id', $userId);

        $items = $items->get();
    	return $items;
	}

	/**
    * get user visits over user fields
    * @param  array  $fields
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getByFields ($fields = [])
	{
		if (empty($fields)) return null;
		$items = self::select('*');
		foreach ($fields as $k => $v)
		{
			$items->where ($k, $v);
		}
		$items = $items->get();

		if (count($items) == 1)
			$items = $items[0];
		return $items;
	}

	/**
    * update user visite
    * @param  int  $id
    * @return void 
    */
	public static function updateVisit ($id)
	{
		$user 	= Auth::user();
		if (empty ($user)) abort (404);

		$aFields = [
			'user_id_prosm' => $id,
			'ank_user_id' 	=> $user->user_id
		];
		$ankVisits = self::getByFields ($aFields);
		if (!empty($ankVisits))
		{
			$ankVisits->ank_time = time();
			$ankVisits->save();
		}
	}

	/**
    * insert user visite
    * @param  int  $id
    * @return void 
    */
	public static function insertVisit ($id)
	{
		$user 	= Auth::user();
		if (empty ($user)) abort (404);

		$aFields = [
			'user_id_prosm'		=> $id,
			'ank_user_id'		=> $user->user_id,
			'ank_time'			=> time()
		];

		$oAnketVisit = new AnketVisit ($aFields);
		$oAnketVisit->save();
	}

	/**
    * remove old visits
    * @param  int  $days
    * @return void 
    */
	public static function removeOld ($days)
	{
		$time = \Carbon\Carbon::now()->subDays($days)->toArray();
		AnketVisit::where('ank_time', '<', ($time['timestamp']))->delete();
	}

	/**
    * get user
    */
	public function user()
	{
    	return $this->hasOne(User::class, 'user_id', 'user_id_prosm')
					->with('city')
					->with('photo');
	}
}
