<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
	use HasFactory;
	protected $table = 'names';
	public $timestamps 		= false;

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeGender($query, $sex)
	{
		return $query->where('gender', '=', $sex);
	}

	public function scopeNameId($query, $id)
	{
		return $query->where('name_id', '=', $id);
	}
}