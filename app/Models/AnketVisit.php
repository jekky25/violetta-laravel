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
