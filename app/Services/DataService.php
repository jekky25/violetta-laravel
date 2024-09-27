<?
namespace App\Services;

use Carbon;

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

	/**
	* make last visit
	* @param integer $time
	*
	* @return string
	*/
	public function lastVisit($time)
	{
		$timestamp = time();
		$dateCheck = date("d-m-y",$time);
		$timeCheck = date("d-m-y",$timestamp);
		$timeCheckIs = date("d-m-y",$timestamp- (60*60*24));
		if ($dateCheck == $timeCheck)	return date("Сегодня",$time);
		if ($dateCheck == $timeCheckIs)	return date("Вчера",$time);
		return date("d.m.y.",$time);
	}

	/**
	* returns the date by age
	*
	* @return Carbon\Carbon
	*/
	public function birthAround($age)
	{
		return Carbon\Carbon::now()->add(-$age, 'year')->format('Y-m-d');
	}
}