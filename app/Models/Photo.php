<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\CategoryHome;

class Photo extends Model
{
	use HasFactory;

	protected $table = 'fotos';

	public function getCountPhotos ()
	{
		$count = self::select('fotos_id')->count();
		return $count > 0 ? $count : 0;
	}
}
