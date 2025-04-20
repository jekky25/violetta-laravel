<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Requests\UserBestRequest;

class UserBestFilter extends Filter
{
	public const KEYS_TO_INT = ['get_sex'];

	public function __construct(UserBestRequest $request)
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
		$this->hasPhotos();
		$this->confirmed();
		$this->top100();
		return $this->builder;
	}

	/**
	 * @return Builder
	 */
	protected function active(): Builder
	{
		return $this->builder->where('active', 1);
	}

	/**
	 * @return Builder
	 */
	protected function hasPhotos(): Builder
	{
		return $this->builder->where('photos_count', '>', 0);
	}

	/**
	 * @return Builder
	 */
	protected function confirmed(): Builder
	{
		return $this->builder->where('confirm_email', 1);
	}

	/**
	 * @return Builder
	 */
	protected function top100(): Builder
	{
		return $this->builder->where('top100', '>', 0);
	}
}
