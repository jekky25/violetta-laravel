<?php
declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasCreatedFrom
 *
 * @method Builder scopeCreatedFrom($query, int $timeStamp)
 */
trait HasCreatedFrom
{
	/**
 	 * @param Builder $query
	 * @param int $timeStamp
	 * @return Builder
 	*/
	public function scopeCreatedFrom($query, int $timeStamp)
	{
		return $query->where('create_time', '>', $timeStamp);
	}
}