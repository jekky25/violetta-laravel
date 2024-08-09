<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
Use App\Interfaces\UserInterface;
use App\Services\LengthPager;
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

	/**
	* get profile from the top100 reiting
	* @param  int $sex
	* @param  int $count
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getTop100($sex, $count)
	{
		$items = User::select(['user_id', 'user_reiting', 'user_name', 'user_birth_date', 'user_city'])
		->where('user_sex', $sex)
		->where('user_active', 1)
		->where('user_fotos', '>', 0)
		->where('user_confirm_email', 1)
		->with('city') 
		->with('photo')
		->limit ($count)
		->orderBy('user_top100', 'desc')
		->get();
		
		foreach ($items as &$_item)
		{
			$_item->photo = $_item->photo[0];
		}
		$items = count ($items) > 1 ? $items : $items[0];
		return ($items);
	}

	/**
	* get num of the profiles
	* @param  int $sex
	* @return int
	*/
	public function getCountAnkets ($sex)
	{
		$count = User::select('user_id')
		->where('user_sex', $sex)
		->where('user_active', 1)
		->count();
		return $count > 0 ? $count : 0;
	}

	/**
	* get profiles who celebrates a birthday today
	* @param  int $count
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getBirthday($count = 0)
	{
		$tDay 	= \Carbon\Carbon::now()->format('d');
		$tMonth = \Carbon\Carbon::now()->format('m');
		$tDate	= '____-'. $tMonth . '-' .$tDay;

		$items = User::select(['user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->where('user_active', 1)
		->where('user_birth_date', 'LIKE', $tDate)
		->with('city')
		->with('photo')
		->paginate($count);
		$items = LengthPager::makeLengthAware($items, $items->total(), $count);
		$items = User::addProps($items);

		return $items;
	}

	/**
	* get the best profiles
	* @param  int $count
	* @param  int $sex
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getBest($count = 0, $sex)
	{
		$items = User::select(['*'])
		->where('user_active', 1)
		->where('user_sex', $sex)
		->where('user_fotos', '>', 0)
		->where('user_confirm_email', 1)
		->where('user_top100', '>', 0)
		->with('city')
		->with('photo')
		->orderBy('user_top100', 'desc')
		->paginate($count);
		$items = LengthPager::makeLengthAware($items, $items->total(), $count);
		$items = User::addProps($items);

		return $items;
	}

	/**
	* get profiles of who watched
	* @param  int $count
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getViews($count = 0)
	{
		$time	= \Carbon\Carbon::now()->subDays(30)->timestamp;
		$items 	= User::select(['user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->where('user_active', 1)
		->with('city')
		->with('city')
		->with('anketVisit')
		->whereExists(function ($query) use ($time) {
			$query->select(DB::raw(1))
				  ->from('anket_visit')
				  ->where('anket_visit.ank_time', '>', $time)
				  ->whereRaw('users_news.user_id = anket_visit.ank_user_id');
		})
		->paginate($count);

		$items = LengthPager::makeLengthAware($items, $items->total(), $count);
		$items = User::addProps($items);

		return $items;
	}
}