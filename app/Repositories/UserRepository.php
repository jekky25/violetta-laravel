<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
Use App\Interfaces\UserInterface;
use App\Services\LengthPager;
use App\Models\User;
use App\Traits\SearchByParams;

class UserRepository implements UserInterface {
	use SearchByParams;
	private $id;
	private $ankets;
	/**
	* get select from model
	* @param  string|array $param
	* @return User;
	*/
	public function select($param)
	{
		return User::select($param);
	}

	/**
	* get id
	* @return integer;
	*/
	public function getId()
	{
		return ($this->id > 0 ? $this->id : 0);
	}

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
		$items = self::addProps($items);
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
	public function getCountAnkets($sex)
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
		$items = self::addProps($items);

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
		$items = self::addProps($items);

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
		$items = self::addProps($items);

		return $items;
	}

	/**
	* get profile for auth
	* @param  string $login
	* @param  string $pass
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByLoginAndPass($login, $pass)
	{
		if (empty($login) or empty($pass)) return false;
		return User::select(['user_id'])
				->where('user_login', $login)
				->where('user_hash', md5($pass))
				->first();
	}

	/**
	* get profile over user_id and confirmation code
	* @param  int $id
	* @param  string $code
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByIdAndConfirmCode($id, $code)
	{
		if ((int)($id) == 0 or empty($code)) return false;
		return User::select(['*'])
				->where('user_id', $id)
				->where('user_submit_code', addslashes($code))
				->first();
	}

	/**
	* get maximal reiting for all females or males with active profiles
	* @param  int $isex
	* @return integer
	*/
	public function getMaxReiting($sex)
	{
		$item = User::select(['*'])
		->where('user_active', 1)
		->where('user_sex', $sex)
		->max('user_reiting');
		return $item;
	}

	/**
	* get the most popular profiles
	* @param  int $count
	* @param  int $sex
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getPopul($count = 0, $sex)
	{
		$items = User::select(['user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->where('user_sex', $sex)
		->with('city')
		->with('photo')
		->with('anketVisit')
		->whereExists(function ($query) {
			$query->select(DB::raw(1))
					->from('anket_visit')
					->whereRaw('users_news.user_id = anket_visit.user_id_prosm');
		})
		->orderBy('user_id', 'desc')
		->paginate($count);
		$items = self::addProps($items);
		return $items;
	}

	/**
	* add properties to this repository
	* @param  \Illuminate\Database\Eloquent\Collection $items
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function addProps($items)
	{
		foreach ($items as &$_item)
		{
			if (count ($_item->photo) > 0)
				$_item->photo 		= $_item->photo[0];
		}
		return $items;
	}

	/**
	* get profiles by option
	* @param  int $count
	* @param  int $sex
	* @param  string $op
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getOp($count = 0, $sex, $op)
	{
		$items = User::select(['user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->where('user_sex', $sex)
		->with('city')
		->with('photo');
		if (!empty ($op['birthDate']))
			$items = $items->where('user_birth_date', '>', $op['birthDate']);
		if (!empty ($op['birthDate2']))
			$items = $items->where('user_birth_date', '<', $op['birthDate2']);
		$items = $items->orderBy('user_id', 'desc')->paginate($count);
		$items = LengthPager::makeLengthAware($items, $items->total(), $count);
		$items = self::addProps($items);

		return $items;
	}

	/**
	* get a profile by id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getById($id)
	{
		$user = Auth::user();
		$item = User::select('*')
		->where ('user_id', $id)
		->where ('user_active', 1);
		if (empty ($user))
		{
			$item->where('user_confirm_email', 1);
		}
		$item->with('diary')
				->with('photo.comment.user.photo')
				->with('city')
				->with('region')
				->with('country');

		$item = $item->firstOrFail();
		return $item;
	}

	/**
	* get a profile by id without relations
	* @param  int $id
	* @param  array $width
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getJustById($id, $width = [])
	{
		$item = User::select('*')
		->where ('user_id', $id);
		if (!empty ($width))
		{
			foreach ($width as $w)
			{
				$item->with($w);
			}
		}
		$item = $item->first();
		if (empty ($item)) return null;
		return $item;
	}

	/**
	* get a profile by E-mail
	* @param  string $email
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByEmail($email)
	{
		$item = User::select('*')
		->where ('user_mail', $email)
		->first();
		return $item;
	}

	/**
	* get a profile by login
	* @param  string $login
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByLogin($login)
	{
		$item = self::select('*')
		->where ('user_login', $login)
		->first();
		return $item;
	}

	/**
	* create an user
	* @param  array $request
	* @return void
	*/	
	public function create($request) 
	{
		try {
			$user = User::create($request);
			$this->id = $user->getKey();
		} catch (\Exception $e) {
			throw new \Exception('Failed to create an User '.$e->getMessage());
		}
	}

	/**
	* update an user
	* @param  ProfileMainRequest $request
	* @return void
	*/	
	public function update($user, $request)
	{
		try {
			User::find($user->user_id)->update([
				'user_sex'				=> $request->user_sex,
				'user_name' 			=> $request->user_name,
				'user_birth_date' 		=> $request->user_birth_date,
				'user_country' 			=> $request->user_country,
				'user_region' 			=> $request->user_region,
				'user_city' 			=> $request->user_city,
				'user_refresh_date' 	=> $request->user_refresh_date,
				'user_refresh_date_t' 	=> $request->user_refresh_date_t,
				'user_session_time' 	=> $request->user_session_time
			]);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update User. '.$e->getMessage());
		}
	}

	/**
	* update an user from the second edit page
	* @param  ProfileSecondRequest $request
	* @return void
	*/	
	public function secondUpdate($user, $request)
	{
		try {
			User::find($user->user_id)->update([
				'user_sex_orient'		=> $request->user_sex_orient,
				'user_target_meet'		=> $request->user_target_meet,
				'user_speak_lang'		=> $request->user_speak_lang,
				'user_body'				=> $request->user_body,
				'user_height'			=> $request->user_height,
				'user_weight'			=> $request->user_weight,
				'user_hair_color'		=> $request->user_hair_color,
				'user_hair_type'		=> $request->user_hair_type,
				'user_eyes'				=> $request->user_eyes,
				'user_education'		=> $request->user_education,
				'user_smoke'			=> $request->user_smoke,
				'user_spirt'			=> $request->user_spirt,
				'user_sem_polozh'		=> $request->user_sem_polozh,
				'user_children'			=> $request->user_children,
				'user_help_money'		=> $request->user_help_money,
				'user_interests'		=> $request->user_interests,
				'user_icq'				=> $request->user_icq,
				'user_url'				=> $request->user_url,
				'user_phone'			=> $request->user_phone,
				'user_description'		=> $request->user_description,
				'user_refresh_date'		=> $request->user_refresh_date,
				'user_refresh_date_t'	=> $request->user_refresh_date_t,
				'user_session_time'		=> $request->user_session_time,
				'user_lastvisit'		=> $request->user_lastvisit,
				'user_odobreno'			=> $request->user_odobreno,
			]);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update User. '.$e->getMessage());
		}
	}

	/**
	* get profiles on the search page
	* @param SearchRequest $request
	* @param array $params
	* @return void
	*/	
	public function getBySearch($request, $params) 
	{
		if (!isset ($request->send)) return null;
		$this->ankets = $this->select('*')->where ('user_active', 1);
		$this->getBySex($params['find_sex'], $params['sex']);
		$this->getByPhoto($params['photo']);
		$this->getByAgeMin($params['age_min']);
		$this->getByAgeMax($params['age_max']);
		$this->getByHeightMin($params['height_min']);
		$this->getByHeightMax($params['height_max']);
		$this->getByWeightMin($params['weight_min']);
		$this->getByWeightMax($params['weight_max']);
		$this->getByBody($params['body']);
		$this->getByHairType($params['hair_type']);
		$this->getByEyes($params['eyes']);
		$this->getByCountry($params['country']);
		$this->getByRegion($params['region']);
		$this->getByCity($params['city']);
		$this->ankets = $this->ankets->orderBy('user_refresh_date_t', 'desc')->paginate($params['anket_per_page']);
		$this->ankets = LengthPager::makeLengthAware($this->ankets, $this->ankets->total(), $params['anket_per_page']);
		$this->ankets->appends(request()->query());
		$this->ankets = self::addProps($this->ankets);
		return $this->ankets;
	}
}