<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Requests\UserViewsRequest;
use Illuminate\Support\Facades\DB;

class UserViewsFilter extends Filter
{
	public function __construct(UserViewsRequest $request)
	{
		parent::__construct($request);
	}

	/**
	 * Invoke this method after applying 
	 *
	 * @return Builder
	 */
	protected function afterBuild() :Builder
	{
		$this->active();
		$this->exists();
		return $this->builder;
	}

	/**
	 * @return Builder
	 */
	protected function active() :Builder
	{
		return $this->builder->where('user_active', 1);
	}

	/**
	 * @return Builder
	 */
	protected function exists() :Builder
	{
		$time	= \Carbon\Carbon::now()->subDays(30)->timestamp;
		return $this->builder->whereExists(function ($query) use ($time) {
			$query->select(DB::raw(1))
				  ->from('anket_visit')
				  ->where('anket_visit.create_time', '>', $time)
				  ->whereRaw('users_news.user_id = anket_visit.user_id');
		});
	}
}