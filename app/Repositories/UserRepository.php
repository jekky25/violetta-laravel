<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
Use App\Interfaces\UserInterface;
use App\Interfaces\AnketVisitInterface;
use App\Models\User;
use App\Repositories\PhotoRepository;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserInterface
{
	private $id;
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
	 * @param int $count
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function newFaces(int $count)
	{
		$items = User::select(['users_news.id', 'active', 'name', 'sex', 'birth_date', 'make_date_t', 'city_id', 'photos_count', 'sex_orient', 'partner_age_min', 'partner_age_max'])
			->join('fotos', 'users_news.id', '=', 'fotos.user_id')
			->where('photos_count', '>', 0)
			->confirmed()
			->active()
			->where('main_picture', 1)
			->with('city')
			->with('photo')
			->limit($count)
			->orderBy('make_date_t', 'desc')
			->get();
		$items = self::addProps($items);
		return $items;
	}

	/**
	 * get profile from the top100 rating
	 * @param  int $sex
	 * @param  int $count
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getTop100($sex, $count)
	{
		$items = User::select(['id', 'rating', 'name', 'birth_date', 'city_id'])
			->sex($sex)
			->active()
			->where('photos_count', '>', 0)
			->confirmed()
			->with('city')
			->with('photo')
			->limit($count)
			->orderBy('top100', 'desc')
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
		foreach ($items as &$_item) {
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
		foreach ($items as &$_item) {
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
		$count = User::select('id')->sex($sex)->active()->count();
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
		$percent = round(($this->getCountAnkets($sex) / $totalAnkets) * 100);
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
	 */
	public function getByLoginAndPass(string $login, string $pass): Collection|null
	{
		if (empty($login) || empty($pass)) return null;
		
		$user = User::where('login', $login)->first();

		if (!$user) return null;
		if (Hash::check($pass, $user->hash)) return $user;

    	if (md5($pass) === $user->hash) {
			$user->hash = Hash::make($pass);
			$user->save();
			return $user;
		}
    	return null;
	}

	/**
	 * get profile over id and confirmation code
	 * @param  int $id
	 * @param  string $code
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getByIdAndConfirmCode($id, $code)
	{
		if ((int)($id) == 0 or empty($code)) return false;
		return User::select(['*'])->whereKey($id)->where('submit_code', addslashes($code))->firstOrFail();
	}

	/**
	 * get maximal rating for all females or males with active profiles
	 * @param  int $isex
	 * @return integer
	 */
	public function getMaxRating($sex)
	{
		return User::select(['*'])->active()->sex($sex)->max('rating');
	}

	/**
	 * get a profile by id
	 * @param  int $id
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getById($id)
	{
		$user = Auth::user();
		$item = User::select('*')->whereKey($id)->active();
		if (empty($user)) {
			$item->confirmed();
		}
		$item->with('diary')
			->with('photo.comment.user.photo')
			->with('city')
			->with('region')
			->with('country');
		return $item->firstOrFail();
	}

	/**
	 * get a profile by id without relations
	 * @param  int $id
	 * @param  array $width
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getJustById($id, $width = [])
	{
		$item = User::select('*')->whereKey($id);
		if (!empty($width)) {
			foreach ($width as $w) {
				$item->with($w);
			}
		}
		$item = $item->first();
		if (empty($item)) return null;
		return $item;
	}

	/**
	 * get a profile by E-mail
	 * @param  string $email
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getByEmail($email)
	{
		return self::select('*')->email($email)->first();
	}

	/**
	 * get a profile by login
	 * @param  string $login
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getByLogin($login)
	{
		return self::select('*')->login($login)->first();
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
			throw new \Exception('Failed to create an User ' . $e->getMessage());
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
			User::find($user->id)->update($params);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update User. ' . $e->getMessage());
		}
	}

	/**
	 * get profiles on the search page
	 * @param Filter $filter
	 * @param Request $request
	 * @param string $order
	 * @return Collection
	 */
	public function getBySearch($filter, $request, $order = 'refresh_date_t')
	{
		return User::filter($filter, $request)->confirmed()->orderBy($order, 'desc')->paginate(config('pagination.profiles'));
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
			$this->anketVisit = new AnketVisitRepository();
			$this->anketVisit->destroyAllByUserId($user->id);
			$user->delete();
		} catch (\Exception $e) {
			throw new \Exception('Failed to remove User. ' . $e->getMessage());
		}
	}

	/**
	 * get fields from the array for web forms
	 * @param  array $fields
	 * @return array
	 */
	public function fields(array $fields): array
	{
		$ar = [];
		foreach ($fields as $name) {
			$className	= 'App\\Models\\' . $name;
			$ar[$name]		= $className::select('*')->orderBy('name', 'asc')->get();
		}
		return $ar;
	}
}
