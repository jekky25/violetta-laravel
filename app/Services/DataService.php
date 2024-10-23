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
	public function getAges()
	{
		$items = [];
		for ($i= (17 + 1); $i < 100; $i++)
			$items[] = $i;
		return $items;
	}

	/**
	* make months of the year
	*
	* @return array
	*/
	public function getMonths()
	{
		return [
			0	=> '-выберите-',
			1	=> 'Января',
			2	=> 'Февраля',
			3	=> 'Марта',
			4	=> 'Апреля',
			5	=> 'Мая',
			6	=> 'Июня',
			7	=> 'Июля',
			8	=> 'Августа',
			9	=> 'Сентября',
			10	=> 'Октября',
			11	=> 'Ноября',
			12	=> 'Декабря'
		];
	}

	/**
	* make a list of years
	*
	* @return array
	*/
	public function getYears()
	{
		$years		= [];
		$today		= getdate();
		$todayYear	= $today['year'];
		for ($i=1900; $i < ($todayYear - 17);$i++) {
			$years [] = $i;
		}
		return $years;
	}

	/**
	* make days in the month
	*
	* @return array
	*/
	public function getDays()
	{
		$items = [];
		for ($i=0; $i < 32;$i++) {
			$items [] = $i;
		}
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

	/**
	* count age
	* @param $birth_date
	*
	* @return integer
	*/
    public function age($birth_date)
	{
		preg_match("/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/",$birth_date,$pockets_old);
		$now_date 	= date("Y-m-d");
		preg_match("/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/",$now_date,$pockets_new);
		$old 		= $pockets_old[2].$pockets_old[3].$pockets_old[4];
		$new 		= $pockets_new[2].$pockets_new[3].$pockets_new[4];
  		$age 		= $new - $old;
  		$lenght 	= strlen($age);
  		$age_fin 	= substr($age,0,($lenght-4));
  		return $age_fin;
	}
}