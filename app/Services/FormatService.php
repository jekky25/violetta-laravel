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

	/**
	* make range of heights
	*
	* @return array
	*/
    public function getHeights()
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
    public function getWeights()
	{
		$items = [];
		for ($i= (PARTNER_WEIGHT_MIN + 1); $i < 131; $i++)
		{
  			$items[] = $i;
		}
		return $items;
	}

	/**
	* make block <select>...</select>
	* @param string $name
	* @param string $className
	* @param integer $value
 	* @param integer $mode
	* @return string
	*/
	public function BlockSelect($name, $className, $value, $mode)
	{
		if (!is_array ($value))
		{
			try {
				$unserValue = unserialize($value);
			} catch (\Exception $e) {}
		}

		$value		= isset ($unserValue) && is_array ($unserValue) ? $unserValue : $value;
		$className	= 'App\\Models\\' . $className;
		$items		= $className::select('*')->orderBy('name','asc')->get();
		$str		= '<select style="width: 150px" name="' . $name . '">';
		$str		.= '<option value="0"';
		$str		.= $value == '0' 	? ' selected' 		: '';
		$str		.= $mode == 1 		? '>-выберите-' 	: '>-не важно-';

		if ($mode == 2)
		{
			$out = [];
			foreach ($items as $_item)
			{
				$_item->selected = is_array($value) ? (in_array($_item->id, $value) 	? 1 			: 0)
													: ($value == $_item->id 			? ' selected' 	: '');
				$out [] = $_item;
			}
			return $out;
		}

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
	* preparation properties frome array
	* @param array $prop
	* @param array $arr
	* @return array
	*/
	public function preparePropfromArray($prop, $ar)
	{
		if (!is_array($prop)) return [];
		foreach ($ar as $k => $v)
		{
			if (in_array($k, $prop)) $arOut[$v]['selected'] = 1;
		}
		return !empty($arOut) ? $arOut : [];
	}

	/**
	* get text for top100 settings page
	* @return string
	*/
	public function getTextTop100()
	{
		$text = '<p>Чтобы поднять анкету в ТОПе нашего сайта, Вам необходимо выполнить <strong>всего 3 условия:</strong></p>
		<p>1. Иметь регистрацию на нашем сайте;</p>
		<p>2. У вас должна быть загружена хотя бы одна фотография;</p>
		<p>3. Вам необходимо подтвердить желание участвовать в ТОПе.</p>
		<p><br /></p>';
		return session('textTop100') ?: $text;
	}

	/**
	* get text for top100гзвфеу settings page
	* @return string
	*/
	public function getTextTop100Update()
	{
		$text = '<p style="color:#f00">Вы не можете попасть в ТОП, т.к. не выполнено одно из условий</p>
						<p>Чтобы стать участником ТОПа нашего сайта, Вам необходимо выполнить <strong>всего 3 условия:</strong></p>
						<p>1. Иметь регистрацию на нашем сайте;</p>
						<p style="color:#f00">2. У вас должна быть загружена хотя бы одна фотография;</p>
						<p>3. Вам необходимо подтвердить желание участвовать в ТОПе.</p>
						<p><strong>Перейти в раздел <a class="name" href="' . route ('registration.edit.photo') . '">Мои фото</a></strong></p>
						<p><br></p>';
		return session('textTop100') ?: $text;
	}

	/**
	* get text for the form on the top100 settings page
	* @return string
	*/
	public function getFormToTop()
	{
		$formToTop 	= '<form name="anketa" action="' . route ('registration.top100.post') . '" method="post">' . 
						csrf_field() . '
						<center>
						<input type="submit" name="otsil" value="Поднять анкету" />
						</center>
					</form>';
		return session('formToTop') ?: $formToTop;
	}

	/**
	* get text for the form on the top100Update settings page
	* @return string
	*/
	public function getFormToTopUpdate()
	{
		$formToTop 	= '<form name="anketa" action="' . route ('registration.top100.post') . '" method="post">' .
						csrf_field() . '
						<input type="submit" name="otsil" value="Попасть в ТОП" /></form>';
		return session('formToTop') ?: $formToTop;
	}

	/**
	* out age type
	* @param $age
	*
	* @return string
	*/
	public function ageType(int $age)
	{
		$ageFin = substr($age,-1,1);
  		if ( ($age >10 && $age <20) || $ageFin <=0	) return "лет";
		if ( $ageFin ==1 ) return "год";
		if ( $ageFin >=2 && $ageFin<=4 ) return "года";
		return "лет";
	}
}