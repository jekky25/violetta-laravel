<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Requests\UserPopularRequest;

class UserPopularFilter extends Filter
{
	public const KEYS_TO_INT = ['get_sex'];

	public function __construct(UserPopularRequest $request)
	{
		parent::__construct($request);
	}

	/**
	 * @param int $value
	 * @return Builder
	 */
	protected function getSex(int $value): Builder
	{
		return $this->builder->where('sex', $value);
	}

	/**
	 * Invoke this method after applying 
	 *
	 * @return Builder
	 */
	protected function afterBuild(): Builder
	{
		$this->active();
		$this->exists();

		return $this->builder;
	}

	/**
	 * @return Builder
	 */
	protected function active(): Builder
	{
		return $this->builder->where('user_active', 1);
	}

	/**
	 * @return Builder
	 */
	protected function exists(): Builder
	{
		return $this->builder->whereExists(function ($query) {
			$query->select(DB::raw(1))
				->from('anket_visit')
				->whereRaw('users_news.user_id = anket_visit.user_id_prosm');
		});
	}
}
