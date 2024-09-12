<?
namespace App\Services;

class DataService
{
	/**
	* make range of ages
	*
	* @return array
	*/
	public static function getAges()
	{
		$items = [];
		for ($i= (17 + 1); $i < 100; $i++)
			$items[] = $i;
		return $items;
	}
}