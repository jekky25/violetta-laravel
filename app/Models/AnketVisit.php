<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Helpers\Helper;

class AnketVisit extends Model
{
	use HasFactory;

	protected $table = 'anket_visit';

	public function visitsNew($user)
	{
		$items = self::select('*')
		->where('user_id_prosm', $user->user_id)
		->where('ank_time', '>', $user->user_lastvisit_views)
        ->get();
    	return $items;
	}


	public function getPopul($count = 0, $sex)
	{
		$items = self::select(['user_id_prosm', 'id', 'ank_user_id', 'ank_time'])
		->with('user')
		->whereHas('user', function ($query) use ($sex) {
			$query->where('user_sex', $sex);
		})
		->groupBy(['user_id_prosm','id', 'ank_user_id', 'ank_time'])
		->orderBy('user_id_prosm', 'desc')
		->paginate($count);
		foreach ($items as &$_item)
		{
			$_item->user->user_age 			= Helper::age($_item->user->user_birth_date);
			$_item->user->user_age_type 	= Helper::ageType($_item->user->user_age);
			if (count ($_item->user->photo) > 0)
				$_item->user->photo 		= $_item->user->photo[0];
		}

		return $items;
	}

	public function user()
	{
    	return $this->hasOne(User::class, 'user_id', 'user_id_prosm')
					->with('city')
					->with('photo');
	}
}
