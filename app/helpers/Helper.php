<?php
namespace App\Helpers;

class Helper {

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

}
