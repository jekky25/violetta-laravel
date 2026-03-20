<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanList extends Model
{
	use HasFactory;
	protected $table = 'ban_list';

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeIp($query, $ip)
	{
		return $query->where('ip', $ip);
	}
}
