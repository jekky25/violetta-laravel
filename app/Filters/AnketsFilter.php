<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Requests\AnketsRequest;
use App\Services\FormatService;

class AnketsFilter extends Filter
{
	public const KEYS_TO_INT = ['get_sex', 'age'];

	public function __construct(AnketsRequest $request, formatService $format)
	{
		parent::__construct($request);
		$this->format = $format;
	}

	/**
	 * @param int $value
	 * @return Builder
	 */
	protected function getSex(int $value) :Builder
	{
		return $this->builder->where('user_sex', $value);
	}

	/**
	 * @param int $value
	 * @return Builder
	 */
	protected function age(int $value) :Builder
	{
		$op	= $this->format->getRange($value);
		if (!empty($op['birthDate'])) $this->builder->where('user_birth_date', '>', $op['birthDate']);
		if (!empty($op['birthDate2'])) $this->builder->where('user_birth_date', '<', $op['birthDate2']);
		return $this->builder;
	}

	/**
	 * Invoke this method after applying 
	 *
	 * @return Builder
	 */
	protected function afterBuild() :Builder
	{
		$this->active();

		return $this->builder;
	}

	/**
	 * @return Builder
	 */
	protected function active() :Builder
	{
		return $this->builder->where('user_active', 1);
	}
}