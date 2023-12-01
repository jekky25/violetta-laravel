<?php
namespace App\Helpers;
use Carbon;

class Helper {

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

    public function __construct(){
    }

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
	 * make range of heights
	 *
	 * @return array
	 */
    public static function getHeights()
	{
		$items = [];
		for ($i= (PARTNER_HEIGHT_MIN + 1); $i < 221; $i++)
		{
  			$items[] = $i;
		}
		return $items;
	}

	/**
	 * make range of weights
	 *
	 * @return array
	 */
    public static function getWeights()
	{
		$items = [];
		for ($i= (PARTNER_WEIGHT_MIN + 1); $i < 131; $i++)
		{
  			$items[] = $i;
		}

		return $items;
	}

	/**
	 * count age
	 * @param $birth_date
	 *
	 * @return integer
	 */
    public static function age($birth_date)
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

	/**
	 * out age type
	 * @param $age
	 *
	 * @return string
	 */
	public static function ageType($age)
	{
  		$age = (int) $age;

  		if ($age >10 && $age <20)
    		$type = "лет";
  		else
  		{
			$age_fin = substr($age,-1,1);
			if ($age_fin <=0 )
    			$type = "лет";
			elseif ($age_fin ==1 )
				$type = "год";
			elseif ($age_fin >=2 && $age_fin<=4)
				$type = "года";
			else
				$type = "лет";
		}
  		return $type;
	}

	/**
	 * Type age from to
	 * @param $age
	 *
	 * @return string
	 */
	public static function ageType2($age)
	{
		$age = intval($age);
		if ($age >10 && $age <20)
			$type = "лет";
		else
		{
			$age_fin = substr($age,-1,1);
			if ($age_fin <=0 )
				$type = "лет";
			elseif ($age_fin ==1 )
				$type = "года";
			elseif ($age_fin >=2 && $age_fin<=4)
				$type = "лет";
			else
				$type = "лет";
		}
		return $type;
	}

	/**
	 * Out to xml
	 * @param $obj
	 *
	 * @return void
	 */
	public static function outToXml ($obj)
	{
		$startStr = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
		return response()
		->view ('xml.location', [
			'obj'		=> $obj,
			'startStr'		=> $startStr,
		])
		->header('Content-Type', 'text/xml')->send();
	}

	/**
	 * formate date from timestamp to date
	 * @param integer $timestamp
	 *
	 * @return string
	 */
	function getDate($timestamp = 0)
	{
		if ($timestamp == 0) $timestamp = time();
  		$date = date("d.m.y.",$timestamp);
		return $date;
	}

	/**
	 * out picture to template
	 * @param integer $picture
	 * @param string $sex
	 *
	 * @return string
	 */
	function outPicture($picture = 0, $sex)
	{
		$picture = (int) $picture;
		if ($picture > 0) return asset('fotos_new/' . $picture . '.jpg');

		$fotoUrl = $sex == MEN ? 'image/no_foto_m_vip.jpg' : 'image/no_foto_w_vip.jpg';
		return asset ($fotoUrl);
	}

	/**
	 * out picture in diaries to template
	 * @param integer $picture
	 * @param string $sex
	 *
	 * @return string
	 */
	function outDiaryPicture($picture = 0, $sex)
	{
		if ($picture > 0) return asset('img/dnevnik/' . $picture . '.jpg');

		$fotoUrl = $sex == MEN ? 'image/no_foto_m_vip.jpg' : 'image/no_foto_w_vip.jpg';
		return asset ($fotoUrl);
	}

	/**
	 * preparation pagination to out to the template
	 * @param array $paginagion
	 *
	 * @return array
	 */
	function preparePagination($pagination = [])
	{
		if (empty ($pagination)) return [];

		$pagination[0] 		= str_replace (' Previous','', $pagination[0]);
		$pagination[0] 		= str_replace ('&laquo;','&lt;', $pagination[0]);
		$ind 				= count ($pagination) - 1;
		$pagination[$ind] 	= str_replace ('Next ','', $pagination[$ind]);
		$pagination[$ind] 	= str_replace ('&raquo;','&gt;', $pagination[$ind]);

		return $pagination;
	}

	/**
	 * formate string to mB or kB
	 * @param string $str
	 *
	 * @return string
	 */
	function formatFileSize($str)
	{
  		$l = strlen($str);
  		if ($l > 6)
  		{
    		$l1 	= substr($str,0,($l-6));
    		$l_len1 = strlen ($l1);
    		$l2 	= substr($str,$l_len1,($l-$l_len1-5));
			$l		= $l1.",".$l2." мБ";
		}
		else
		{
			if ($l < 3)
				$l1 = 0;
			else
				$l1 = substr($str,0,($l-3));
			$l = $l1." кБ";
		}
		return $l;
	}

	//Возвращает дату по возрасту
	function birthAround($age)
	{
		$time = Carbon\Carbon::now()->add(-$age, 'year')->format('Y-m-d');
		return $time;
	}

	/**
	 * make block <select>...</select>
	 * @param string $name
	 * @param string $className
	 * @param integer $value
 	 * @param integer $mode
	 *
	 * @return string
	 */
	function BlockSelect($name,$className,$value = 0,$mode)
	{
		$className = 'App\\Models\\' . $className;
		$items = $className::select('*')->orderBy('name','asc')->get();
		$str 	= '<select style="width: 150px" name="' . $name . '">';
		$str 	.= '<option value="0"';
		$str	.= $value == '0' 	? ' selected' 		: '';
		$str	.= $mode == 1 		? '>-выберите-' 	: '>-не важно-';
		if (!empty ($items))
		{
			foreach ($items as $_item)
			{
				$str .= '  <option value="' . $_item->id . '"';
				$str .= $value == $_item->id ? ' selected' : '';
				$str .= '>' . $_item->name;
			}
		}
		$str .= '</option></select>';
		return $str;
	}

	/**
	 * make query Block
	 * @param array $ar
	 * @param object $items
	 *
	 * @return void
	 */
	function queryBlock($ar, &$items)
	{
		$items->where (function ($query) use ($ar) {
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
	function queryBlockOr($ar, &$items)
	{
		$items->Orwhere (function ($query) use ($ar) {
			$query->where('user_sex', $ar[0]);
			$query->where(function ($query) use ($ar) {
				$query->where('user_sex_orient', $ar[1]);
				$query->orWhere('user_sex_orient', $ar[2]);
			});
		});
	}

	/**
	 * make found string
	 * @param object $ankets
	 * @param integer $count
	 *
	 * @return string
	 */
	public function getFoundStr($ankets, $count = 0)
	{
		$startShow 		= (($ankets->currentPage() - 1) * $count) + 1;
		$endShow		= $ankets->currentPage() * $count;
		$endShow		= $endShow < $ankets->total() ? $endShow : $ankets->total();
				
		$str = 'Найдено анкет: (' . $startShow . '-' . $endShow . ') из ' . $ankets->total();
		return $str;
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
  		if ($dateCheck == $timeCheck)
  		{
    		$date = date("Сегодня",$time);
  		} else if ($dateCheck == $timeCheckIs)
  		{
    		$date = date("Вчера",$time);
  		}
   		else
    		$date = date("d.m.y.",$time);
  		return $date;
	}

	/**
	 * out diary type in cases
	 * @param integer $number
	 *
	 * @return string
	*/
	public function caseDiaryType($number)
	{
		$number = intval($number);
		if ($number >10 && $number <20)
			$type = "записей";
		else
		{
			$number_fin = substr($number,-1,1);
			if ($number_fin <=0 )
				$type = "записей";
			elseif ($number_fin ==1 )
				$type = "запись";
			elseif ($number_fin >=2 && $number_fin<=4)
				$type = "записи";
			else
				$type = "записей";
		}
		return $type;
	}

	/**
	 * get zodiac name by birth date
	 * @param string $birth_date
	 *
	 * @return string
	*/
	public function zodiac($birthDate)
	{
	
		$arZodiac = [];
		$zodiac = self::$zodiacSigns;
		preg_match("/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/",$birthDate,$pockets);

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
}