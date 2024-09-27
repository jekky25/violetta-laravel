<?
namespace App\Services;

use App\Helpers\Helper;

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

	/**
	* preparation ttles for the page with profiles
	* @param  string  $sex
	* @param  int  $op
	*
	* @return array
	*/
	public function prepareAnketTitles($sex = '', $op = '')
	{
		$data				= new DataService;
		$ankTitle			= $sex == 'men' ? 'Анкеты: мужчины' : 'Анкеты: женщины';
		$ankTitleId			= $sex == 'men' ? 'Анкеты мужчин' : 'Анкеты женщин';
		$birthDate			= null;
		$birthDate2			= null;
		switch ($op) {
			case '20':
				$birthDate		= $data->birthAround(20);
				$ankTitle		.= ', до 20 лет';
				$ankTitleId		.= ' до 20 лет';
				break;
		
			case '2025':
				$birthDate		= $data->birthAround(25);
				$birthDate2		= $data->birthAround(19);
				$ankTitle		.= ', 20 - 25 лет';
				$ankTitleId		.= ' 20 - 25 лет';
				break;
			case '2535':
				$birthDate		= $data->birthAround(35);
				$birthDate2		= $data->birthAround(24);
				$ankTitle		.= ', 25 - 35 лет';
				$ankTitleId		.= ' 25 - 35 лет';
				break;
			case "3550":
				$birthDate		= $data->birthAround(50);
				$birthDate2		= $data->birthAround(34);
				$ankTitle		.= ', 35 - 50 лет';
				$ankTitleId		.= ' 35 - 50 лет';
				break;
			case "50":
				$birthDate2 	= $data->birthAround(50);
				$ankTitle		.= ', от 50 лет';
				$ankTitleId		.= ' от 50 лет';
				break;
		}
		return [
			'ankTitle'		=> $ankTitle,
			'ankTitleId'	=> $ankTitleId,
			'birthDate'		=> $birthDate,
			'birthDate2'	=> $birthDate2
		];
	}
}