<?
namespace App\Services;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\LengthPaginator as LengthPaginator;

abstract class LengthPager
{
    /**
     * Create paginator
     *
     * @param  Illuminate\Support\Collection  $collection
     * @param  int     $total
     * @param  int     $perPage
     * @return string
     */
    public static function makeLengthAware($collection, $total, $perPage, $appends = null)
    {
		$trailingSlash = '/';

		$path = Paginator::resolveCurrentPath() . $trailingSlash;

		$paginator = new LengthPaginator(
			$collection->items(), $total, $perPage, Paginator::resolveCurrentPage(), ['path' => $path]
        );
        return $paginator;
    }
}