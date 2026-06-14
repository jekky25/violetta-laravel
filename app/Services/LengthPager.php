<?php
namespace App\Services;

use Illuminate\Pagination\Paginator;
use App\Services\LengthPaginator as LengthPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class LengthPager
{
	/**
	 * Create paginator
	 *
	 * @param  Illuminate\Support\Collection  $collection
	 * @param  int     $total
	 * @param  int     $perPage
	 * @return App\Services\LengthPaginator
	 */
    public static function makeLengthAware($collection, $total, $perPage, $appends = null) :LengthPaginator|LengthAwarePaginator
    {
		$trailingSlash = '/';
		$patern = '.html';
		$path = Paginator::resolveCurrentPath();

		if (str_contains($path, $patern)) return new LengthAwarePaginator($collection->items(), $total, $perPage, Paginator::resolveCurrentPage(), ['path' => $path]);

		$path = $path . $trailingSlash;

		return new LengthPaginator($collection->items(), $total, $perPage, Paginator::resolveCurrentPage(), ['path' => $path]);
    }
}