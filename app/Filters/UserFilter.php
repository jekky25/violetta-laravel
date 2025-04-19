<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Services\DataService;
use App\Requests\SearchRequest;

class UserFilter extends Filter
{
	public const AGE_MIN	= AGE_MIN;
	public const AGE_MAX	= AGE_MAX;
	public const HEIGHT_MIN	= HEIGHT_MIN;
	public const HEIGHT_MAX	= HEIGHT_MAX;
	public const WEIGHT_MIN	= WEIGHT_MIN;
	public const WEIGHT_MAX	= WEIGHT_MAX;

	public const KEYS_TO_INT = ['sex', 'find_sex', 'age_max', 'age_min', 'height_max', 'height_min', 'weight_max', 'weight_min', 'country', 'region', 'city'];
	protected	int $sex = 0;
	protected	int $findSex = 0;

	private $data;

	public function __construct(SearchRequest $request)
	{
		parent::__construct($request);
		$this->data = new DataService;
	}

	/**
	 * Invoke this method after applying 
	 *
	 * @return Builder
	 */
	protected function afterBuild(): Builder
	{
		$this->getBySex();
		return $this->builder;
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function sex(int $value): Builder
	{
		$this->sex = $value > 0 ? $value : 0;
		return $this->builder;
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function findSex(int $value): Builder
	{
		$this->findSex = $value > 0 ? $value : 0;
		return $this->builder;
	}

	/**
	 * @return Builder
	 */
	private function getBySex(): Builder
	{
		if ($this->findSex !== 0 && $this->sex == 0) $this->builder->where('user_sex', $this->findSex);
		else if ($this->findSex == 0 && $this->sex !== 0) {
			if ($this->sex == MEN) {
				$this->builder->where(function ($query) {
					$this->data->queryBlock([MEN, GOMOSEXUAL, BISEXUAL], $query);
					$this->data->queryBlockOr([WOMEN, GETEROSEXUAL, BISEXUAL], $query);
				});
			} else if ($this->sex == WOMEN) {
				$this->builder->where(function ($query) {
					$this->data->queryBlock([WOMEN, GOMOSEXUAL, BISEXUAL], $query);
					$this->data->queryBlockOr([MEN, GETEROSEXUAL, BISEXUAL], $query);
				});
			}
		} else if ($this->findSex !== 0 && $this->sex !== 0) {
			if ($this->sex == MEN) {
				if ($this->findSex == MEN) {
					$this->data->queryBlock([MEN, GOMOSEXUAL, BISEXUAL], $this->builder);
				} else if ($this->findSex == WOMEN) {
					$this->data->queryBlock([WOMEN, GETEROSEXUAL, BISEXUAL], $this->builder);
				}
			} else if ($this->sex == WOMEN) {
				if ($this->findSex == WOMEN) {
					$this->data->queryBlock([WOMEN, GOMOSEXUAL, BISEXUAL], $this->builder);
				} else if ($this->findSex == MEN) {
					$this->data->queryBlock([MEN, GETEROSEXUAL, BISEXUAL], $this->builder);
				}
			}
		}
		return $this->builder;
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function photo(): Builder
	{
		return $this->builder->where('photos_count', '>', 0);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function ageMin(int $value): Builder
	{
		return $value <= self::AGE_MIN	? $this->builder 		: $this->builder->where('birth_date', '<', $this->data->birthAround($value - 1));
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function ageMax(int $value): Builder
	{
		return $value <= self::AGE_MAX	? $this->builder		: $this->builder->where('birth_date', '>', $this->data->birthAround($value));
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function country(int $value): Builder
	{
		return empty($value) ? $this->builder					: $this->builder->where('country_id', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function region(int $value): Builder
	{
		return empty($value) ? $this->builder					: $this->builder->where('region_id', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function city(int $value): Builder
	{
		return empty($value) ? $this->builder					: $this->builder->where('city_id', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function heightMin(int $value): Builder
	{
		return $value <= self::HEIGHT_MIN	? $this->builder 	: $this->builder->where('height', '>=', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function heightMax(int $value): Builder
	{
		return $value <= self::HEIGHT_MAX	? $this->builder  	: $this->builder->where('height', '<=', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function weightMin(int $value): Builder
	{
		return $value <= self::WEIGHT_MIN ? $this->builder		: $this->builder->where('weight', '>=', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function weightMax(int $value): Builder
	{
		return $value <= self::WEIGHT_MAX ? $this->builder		: $this->builder->where('weight', '<=', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function body($value): Builder
	{
		return empty($value) ? $this->builder					: $this->builder->where('body', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function hairType(int $value): Builder
	{
		return empty($value) ? $this->builder					: $this->builder->where('hair_type', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function eyes(int $value): Builder
	{
		return empty($value) ? $this->builder					: $this->builder->where('eyes', $value);
	}

	/**
	 * @param string $value
	 * @return Builder
	 */
	protected function online(): Builder
	{
		return $this->builder;
	}
}
