<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
	use HasFactory;
	protected $table		= 'regions';
	public $timestamps		= false;

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeCountryId($query, $id)
	{
		return $query->where ('country_id', $id);
	}
}