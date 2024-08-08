<?php

namespace App\Repositories;

use App\Interfaces\PhotoInterface;
use App\Models\Photo;

class PhotoRepository implements PhotoInterface {
	/**
	* get count all user pictures
	* @return int
	*/
	public function getCountPhotos ()
	{
		$count = Photo::select('fotos_id')->count();
		return $count > 0 ? $count : 0;
	}
}