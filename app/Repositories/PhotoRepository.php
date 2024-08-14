<?php

namespace App\Repositories;

use App\Interfaces\PhotoInterface;
use App\Models\Photo;

class PhotoRepository implements PhotoInterface {
	private $id;

	/**
	* get id
	* @return integer;
	*/
	public function getId ()
	{
		return ($this->id > 0 ? $this->id : 0);
	}

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

	/**
	* get all puctures by userId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getAllByUserId($id)
	{
		$item = Photo::select('*')
		->where ('user_id', $id)
		->get();
		return $item;
	}

	/**
	* create a photo
	* @param  array $request
	* @return void
	*/	
	public function create($request) {
		try {
			$photo = Photo::create($request);
			$this->id = $photo->getKey();
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Photo '.$e->getMessage());
		}
	}
}