<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	use HasFactory;
	protected $table		= 'city';
	public $timestamps		= false;

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeRegionId($query, $id)
	{
		return $query->where('region_id', $id);
	}
}