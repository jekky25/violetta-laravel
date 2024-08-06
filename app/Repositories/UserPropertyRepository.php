<?php

namespace App\Repositories;

use App\Interfaces\UserPropertyInterface;

abstract class UserPropertyRepository implements UserPropertyInterface {
	protected static $ModelNamePrefix = 'App\\Models\\';
	/**
	* get type of body by id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		$item = self::getFullModelName()::select('*')
		->where ('id', $id)
		->first();
		return $item;
	}
	
	/**
	* get all types of body
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getAll()
	{
		$items = self::getFullModelName()::select('*')
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

	/**
	* get model the full model name 
	* @return string
	*/
	public static function getFullModelName()
	{
		return self::$ModelNamePrefix . static::$classModelName;
	}
}