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
		return self::getFullModelName()::select('*')->whereKey($id)->first();
	}
	
	/**
	* get all types of body
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getAll()
	{
		return self::getFullModelName()::select('*')->orderBy('name', 'asc')->get();
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