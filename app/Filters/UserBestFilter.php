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
	 * @param string $value
	 * @return Builder
	 */
	protected function getSex(string $value) :Builder
	{
		return $this->builder->where('user_sex', $value);
	}

	/**
	 * Invoke this method after applying 
	 *
	 * @return Builder
	 */
	protected function afterBuild() :Builder
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
	protected function active() :Builder
	{
		return $this->builder->where('user_active', 1);
	}

	/**
	 * @return Builder
	 */
	protected function hasPhotos() :Builder
	{
		return $this->builder->where('user_fotos', '>', 0);
	}

	/**
	 * @return Builder
	 */
	protected function confirmed() :Builder
	{
		return $this->builder->where('user_confirm_email', 1);
	}
	
	/**
	 * @return Builder
	 */
	protected function top100() :Builder
	{
		return $this->builder->where('user_top100', '>', 0);
	}
}