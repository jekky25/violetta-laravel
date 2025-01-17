<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Tstr;

class Goroskop extends Model
{
	use HasFactory, Tstr;
	public $timestamps 		= false;
	protected $table		= 'goroskop';
	private $pattern 		= '/{google_baner([0-9]+)}/i';
	private $replacement	= '';

	public function getGorTextAttribute ($val)
    {
		$val = str_replace("\n","<br><br>\n",$val);
		return $this->replaceStringByPattern ($val, $this->pattern, $this->replacement);
    }
}