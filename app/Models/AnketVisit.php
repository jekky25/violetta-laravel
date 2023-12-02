<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Helpers\Helper;

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

	public function getVisitsByUserId($id, $days, $userId = 0)
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

	public function getByFields ($fields = [])
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
}
