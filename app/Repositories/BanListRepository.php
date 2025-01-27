<?php

namespace App\Repositories;

use App\Interfaces\BanListInterface;
use App\Models\BanList;

class BanListRepository implements BanListInterface {

	/**
	* check user ban list by ip address
	* @param  string $ip
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getByIP($ip)
	{
		$item = BanList::select('*')
		->where('ip', $ip)
		->first();
		return $item;
	}
}