<?
namespace App\Services;

use Carbon;

class DataService
{
	public static $zodiacSigns = [
		"1" => "Козерог",
		"2" => "Водолей",
		"3" => "Рыбы",
		"4" => "Овен",
		"5" => "Телец",
		"6" => "Близнецы",
		"7" => "Рак",
		"8" => "Лев",
		"9" => "Дева",
		"10" => "Весы",
		"11" => "Скорпион",
		"12" => "Стрелец"];

	const PATTERN_DATE = '/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/';
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
		$pockets_old = $this->getDataFromString($birth_date);
		$pockets_new = $this->getDataFromString(date("Y-m-d"));
		$old 		= $pockets_old[2].$pockets_old[3].$pockets_old[4];
		$new 		= $pockets_new[2].$pockets_new[3].$pockets_new[4];
  		$age 		= $new - $old;
  		$lenght 	= strlen($age);
  		$age_fin 	= substr($age,0,($lenght-4));
  		return $age_fin;
	}

	/**
	* formate date from timestamp to date
	* @param integer $timestamp
	*
	* @return string
	*/
	public function getDate(int $timestamp)
	{
		if ($timestamp == 0) $timestamp = time();
		return date("d.m.y.", $timestamp);
	}

	/**
	* make query Block
	* @param array $ar
	* @param object $items
	*
	* @return void
	*/
	public function queryBlock($ar, &$items)
	{
		$items->where(function ($query) use ($ar) {
			$query->where('user_sex', $ar[0]);
			$query->where(function ($query) use ($ar) {
				$query->where('user_sex_orient', $ar[1]);
				$query->orWhere('user_sex_orient', $ar[2]);
			});
		});
	}

	/**
	* make or query Block
	* @param array $ar
	* @param object $items
	*
	* @return void
	*/
	public function queryBlockOr($ar, &$items)
	{
		$items->Orwhere(function ($query) use ($ar) {
			$query->where('user_sex', $ar[0]);
			$query->where(function ($query) use ($ar) {
				$query->where('user_sex_orient', $ar[1]);
				$query->orWhere('user_sex_orient', $ar[2]);
			});
		});
	}

	/**
	* transform data string format 0000-00-00 to an array
	* @param string $strData
	*
	* @return array
	*/
	public function getDataFromString($strData)
	{
		preg_match(self::PATTERN_DATE, $strData, $ar);
		return $ar;
	}

	/**
	* get zodiac name by birth date
	* @param string $birth_date
	*
	* @return string
	*/
	public function zodiac($birthDate)
	{
	
		$arZodiac	= [];
		$zodiac		= self::$zodiacSigns;
		$pockets	= $this->getDataFromString($birthDate);
        $pockets[3] = (int)$pockets[3];
        $pockets[4] = (int)$pockets[4];
		switch ($pockets[3]) {
			case '01':
			case '03':
			case '04':
				$id	= $pockets[4] <= 20 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '02':
				$id	= $pockets[4] <= 19 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '05':
			case '06':	
				$id	= $pockets[4] <= 21 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '07':
			case '08':
			case '09':
			case '10':
				$id	= $pockets[4] <= 23 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '11':
				$id	= $pockets[4] <= 22 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '12':
				$id	= $pockets[4] <= 22 ? $pockets[3] : 1;
			break;

			default:
				$id = 1;
			break;
		}
		$arZodiac['zodiac_text'] = $zodiac[$id];

		switch ($id) {
			case 1:
				$arZodiac['zodiac_id'] = 10;
			break;
			case 2:
				$arZodiac['zodiac_id'] = 11;
			break;
			case 3:
				$arZodiac['zodiac_id'] = 12;
			break;
			case 4:
				$arZodiac['zodiac_id'] = 1;
			break;
			case 5:
				$arZodiac['zodiac_id'] = 2;
			break;
			case 6:
				$arZodiac['zodiac_id'] = 3;
			break;
			case 7:
				$arZodiac['zodiac_id'] = 4;
			break;
			case 8:
				$arZodiac['zodiac_id'] = 5;
			break;
			case 9:
				$arZodiac['zodiac_id'] = 6;
			break;
			case 10:
				$arZodiac['zodiac_id'] = 7;
			break;
			case 11:
				$arZodiac['zodiac_id'] = 8;
			break;
			default:
				$arZodiac['zodiac_id'] = 9;
			break;

		}
		return $arZodiac;
	}

	/**
	* move date to the format dd.mm.yyyy
	* ex date_format ($date)
	* @param string $date
	*
	* @return string
	*/
	public function dateFormat($date)
	{
		$pockets	= $this->getDataFromString($date);
		return $pockets[4].".".$pockets[3].".".$pockets[2];
	}

	/**
	* select a separate year, month or day from the date
	* 
	* @param string $date
	* @param integer $mode
	*
	* @return array
	*/
	public function selectFromDate($date, $mode)
	{
		$pockets	= $this->getDataFromString($date);
  		return $pockets[$mode];
	}

	/**
	 * transform day, month and year to data string format 0000-00-00
	 * 
	 * @param integer $day
	 * @param integer $month
	 * @param integer $year
	 *
	 * @return string
	 */
	public function getDateStr($day,$month,$year)
	{
		$day	= $day < 10 	? "0$day" 	: $day;
		$month	= $month < 10 	? "0$month" : $month;
		return "$year-$month-$day";
	}
}