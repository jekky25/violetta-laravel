<?php
declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasUserId
 *
 * @method Builder scopeUserId(Builder $query, int $userId)
 */
trait HasUserId
{
	/**
 	 * @param Builder $query
	 * @param int $userId
	 * @return Builder
 	*/
	public function scopeUserId(Builder $query, int $userId)
	{
	    return $query->where('user_id', $userId);
	}
}