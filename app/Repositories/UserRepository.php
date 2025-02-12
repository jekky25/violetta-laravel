<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
Use App\Interfaces\UserInterface;
use App\Interfaces\AnketVisitInterface;
use App\Services\LengthPager;
use App\Models\User;
use App\Traits\SearchByParams;
use App\Repositories\PhotoRepository;

class UserRepository implements UserInterface {
	use SearchByParams;
	private $id;
	private $ankets;
	public AnketVisitInterface $anketVisitRepository;

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
		->where('main_picture', 1)
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
		return ($this->addParams($items));
	}

	/**
	* add params to the user profiles
	* @param \Illuminate\Database\Eloquent\Collection $items
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	private function addParams($items)
	{
		if ($items->count() == 0) return $items;
		foreach ($items as &$_item)
		{
			$_item->photo = $_item->photo[0];
		}
		return ($items->count() > 1 ? $items : $items[0]);
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
			if (count($_item->photo) > 0)
				$_item->photo 		= $_item->photo[0];
		}
		return $items;
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
	* get a percent of the profiles
	* @param  int $sex
	* @return int
	*/
	public function getPercentOfAnkets($sex)
	{
		$totalAnkets = $this->getCountAnkets(MEN) + $this->getCountAnkets(WOMEN);
		if ($totalAnkets == 0) return 0;
		$percent = round(( $this->getCountAnkets($sex) / $totalAnkets ) * 100);
		return $percent;
	}

	/**
	* get statistic of profiles
	* @return array
	*/
	public function getStatistic()
	{
		return [
				'total_women' 			=> $this->getCountAnkets(WOMEN),
				'total_men' 			=> $this->getCountAnkets(MEN),
				'total_fotos' 			=> (new PhotoRepository)->getCount(),
				'women_ank_percent' 	=> $this->getPercentOfAnkets(WOMEN),
				'total_women_percent' 	=> sprintf('%d%%', $this->getPercentOfAnkets(WOMEN)),
				'men_ank_percent' 		=> $this->getPercentOfAnkets(MEN),
				'total_men_percent' 	=> sprintf('%d%%', $this->getPercentOfAnkets(MEN))
			];
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
				->firstOrFail();
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
		if (!empty($op['birthDate']))
			$items = $items->where('user_birth_date', '>', $op['birthDate']);
		if (!empty($op['birthDate2']))
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
		->where('user_id', $id)
		->where('user_active', 1);
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
		->where('user_id', $id);
		if (!empty($width))
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
		->where('user_mail', $email)
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
	* @param User $user
	* @param array $params
	* @return void
	*/
	public function update($user, $params)
	{
		try {
			User::find($user->user_id)->update($params);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update User. '.$e->getMessage());
		}
	}

	/**
	* update an user from the second edit page
	* @param User $user
	* @param  array $params
	* @return void
	*/
	public function secondUpdate($user, $params)
	{
		try {
			User::find($user->user_id)->update($params);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update User. '.$e->getMessage());
		}
	}

	/**
	* update an user from the partner edit page
	* @param User $user
	* @param  array $params
	* @return void
	*/
	public function partnerUpdate($user, $params)
	{
		try {
			User::find($user->user_id)->update($params);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update User. '.$e->getMessage());
		}
	}

	/**
	* update an user
	* @param User $user
	* @param  array $params
	* @return void
	*/
	public function passUpdate($user, $params)
	{
		try {
			User::find($user->user_id)->update($params);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update User. '.$e->getMessage());
		}
	}

	/**
	* update an user from setting page
	* @param User $user
	* @param  array $params
	* @return void
	*/
	public function settingUpdate($user, $params)
	{
		try {
			User::find($user->user_id)->update($params);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update User. '.$e->getMessage());
		}
	}

	/**
	* get profiles on the search page
	* @param Filter $filter
	* @param Request $request
	* @param string $order
	* @return Collection
	*/
	public function getBySearch($filter, $request, $order = 'user_refresh_date_t')
	{
		return User::filter($filter, $request)->orderBy($order, 'desc')->paginate($request->get('per_page'));
	}

	/**
	* destroy user profile
	* @param  User $user
	* @return void
	*/
	public function destroy($user)
	{
		try {
			(new PhotoRepository)->destroyAllByUser($user);
			$this->anketVisitRepository = new AnketVisitRepository();
			$this->anketVisitRepository->destroyAllByUserId($user->user_id);
			$user->delete();
		} catch (\Exception $e) {
			throw new \Exception('Failed to remove User. '.$e->getMessage());
		}
	}

	/**
	* get fields from the array for web forms
	* @param  array $fields
	* @return array
	*/
	public function fields(array $fields) :array
	{
		$ar = [];
		foreach ($fields as $name)
		{
			$className	= 'App\\Models\\' . $name;
			$ar[$name]		= $className::select('*')->orderBy('name','asc')->get();
		}
		return $ar;
	}
}
