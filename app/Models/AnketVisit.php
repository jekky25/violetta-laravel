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

	public function user()
	{
    	return $this->hasOne(User::class, 'user_id', 'user_id_prosm')
					->with('city')
					->with('photo');
	}
}
