<?php
declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * Class Filter
 */
abstract class Filter
{
	public const KEYS_TO_INT = [];

	protected Builder $builder;
	protected array $except = ['anket_per_page', 'page', 'send'];

	/**
	 * @param FormRequest $request
	 */
	public function __construct(protected FormRequest $request)
	{
	}

	/**
	 * Apply the filter
	 *
	 * @param Builder $builder
	 * @return Builder
	 */
	public function apply(Builder $builder): Builder
	{
		$this->builder = $builder;
		foreach ($this->request->input() as $method => $value) {
			$methodName = $this->toCamel($method);
			if (!$this->passed($method, $value)) continue;

			$value = $this->prepareType($value, $method);

			$this->builder = $this->{$methodName}($value);
		}
		$this->afterBuild();

		return $this->builder;
    }

	/**
	 * Check method for exception
	 *
	 * @param string $method
	 * @return bool
	 */
	protected function isExcept($method) :bool
	{
		return in_array($method, $this->except);
	}

	/**
	 * Prepare type
	 *
	 * @param string|int $value
	 * @param string $method
	 * @return string|int|bool
	 */
	protected function prepareType($value, $method) :string|int|bool
	{
		if (in_array($method, static::KEYS_TO_INT, true)) {
			$value = (int)$value;
		}

		return $value;
	}

	/**
	 * Invoke this method after applying 
	 *
	 * @return Builder
	 */
	protected function afterBuild() :Builder
	{
		return $this->builder;
	}

	/**
	 * Check method
	 *
	 * @param string $method
	 * @param string|int $value
	 * 
	 * @return bool
	 */
	protected function passed($method, $value) :bool
	{
		return	$value === null || $this->isExcept($method) || !method_exists($this, $this->toCamel($method)) || !isset($this->request->validated()[$method]) ? false : true;
	}

	/**
	 * Transform string to camel case
	 *
	 * @param string $method
	 * 
	 * @return string
	 */
	protected function toCamel($method) :string
	{
		return Str::camel($method);
	}
}