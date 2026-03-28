<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Tstr;

class Horoscope extends Model
{
	use HasFactory, Tstr;
	public $timestamps 		= false;
	protected $table		= 'goroskop';
	private $pattern 		= '/{google_baner([0-9]+)}/i';
	private $replacement	= '';

	public function getDescriptionAttribute($val)
	{
		$val = str_replace("\n","<br><br>\n",$val);
		return $this->replaceStringByPattern($val, $this->pattern, $this->replacement);
	}

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeType($query, $type)
	{
		return $query->where('type', $type);
	}
}