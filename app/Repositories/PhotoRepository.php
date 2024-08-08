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

	/**
	* get pucture by id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getById($id)
	{
		$item = Photo::select('*')
		->where ('fotos_id', $id)
		->first();
		return $item;
	}

	/**
	* get the first pucture by userId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getFirstByUserId($id)
	{
		$item = Photo::select('*')
		->where ('user_id', $id)
		->first();
		return $item;
	}
}