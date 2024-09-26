<?
namespace App\Services;


class FormatService
{
	/**
	 * get reiting string
	 * @param string $birth_date
	 *
	 * @return string
	*/
	public function reiting($reit, $maxReit)
	{
		$reiting = round(($reit / $maxReit ) * 1000);
		$reitStr = $reiting / 100;
		if ($reitStr > 7) 
			$str = 70;
		elseif ($reitStr > 5) 
			$str = 56;
		elseif ($reitStr > 3) 
			$str = 42;
		elseif ($reitStr > 2) 
			$str = 28;
		elseif ($reitStr > 1)
			$str = 14;
		else 
			$str = 7;

		return $str;
	}
}