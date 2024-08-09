<?php

namespace App\Repositories;

Use App\Interfaces\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface {
	/**
	* get new faces for the front page
	* @param  int $count
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function newFaces($count)
	{
		$items = User::select(['users_news.user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->join('fotos', 'users_news.user_id', '=', 'fotos.user_id')
		->where('user_fotos', '>', 0)
		->where('user_confirm_email', 1)
		->where('user_active', 1)
		->where('fotos_portret', 1)
		->with('city') 
		->with('photo')
		->limit ($count)
		->orderBy('user_make_date_t', 'desc')
		->get();
		$items = User::addProps($items);
		return $items;
	}
}