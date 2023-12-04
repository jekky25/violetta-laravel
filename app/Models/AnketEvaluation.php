<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnketEvaluation extends Model
{
	use HasFactory;

	protected $table = 'ocenka_anket';

	public function getEvauletions($userIdAct, $uderId)
	{
		$items = self::select('*')
		->where('user_id', $userIdAct)
		->where('user_id_ocenka', $uderId)
        ->get();
    	return $items;
	}

}
