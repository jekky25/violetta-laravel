<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BanList extends Model
{
	use HasFactory;

	protected $table = 'ban_list';

	public static function getByIP($ip)
	{
		$item = self::select('*')
		->where ('ban_ip', $ip)
		->first();
		return $item;
	}
}
