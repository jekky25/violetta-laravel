<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Requests\UserBirthdayRequest;
use App\Services\FormatService;

class UserBirthdayFilter extends Filter
{
	private $format;

	public function __construct(UserBirthdayRequest $request, formatService $format)
	{
		parent::__construct($request);
		$this->format = $format;
	}

	/**
	 * Invoke this method after applying 
	 *
	 * @return Builder
	 */
	protected function afterBuild(): Builder
	{
		$this->active();
		$this->date();
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
	protected function date(): Builder
	{
		return $this->builder->where('birth_date', 'LIKE', $this->format->dataForDB());
	}
}
