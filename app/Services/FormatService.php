<?

namespace App\Services;

class FormatService
{
	/**
	 * get rating string
	 * @param string $birth_date
	 *
	 * @return string
	 */
	public function rating($rate, $maxRate)
	{
		$rating = round(($rate / $maxRate) * 1000);
		$rateStr = $rating / 100;
		if ($rateStr > 7)
			$str = 70;
		elseif ($rateStr > 5)
			$str = 56;
		elseif ($rateStr > 3)
			$str = 42;
		elseif ($rateStr > 2)
			$str = 28;
		elseif ($rateStr > 1)
			$str = 14;
		else
			$str = 7;

		return $str;
	}

	/**
	 * preparation titles for the page with profiles
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
	 * get range for select from the DB
	 * @param  int  $op
	 *
	 * @return array
	 */
	public function getRange($op = '')
	{
		$data				= new DataService;
		$dateStart			= null;
		$dateEnd			= null;
		switch ($op) {
			case '20':
				$dateStart		= $data->birthAround(20);
				break;

			case '2025':
				$dateStart		= $data->birthAround(25);
				$dateEnd		= $data->birthAround(19);
				break;
			case '2535':
				$dateStart		= $data->birthAround(35);
				$dateEnd		= $data->birthAround(24);
				break;
			case "3550":
				$dateStart		= $data->birthAround(50);
				$dateEnd		= $data->birthAround(34);
				break;
			case "50":
				$dateEnd 		= $data->birthAround(50);
				break;
		}
		return [
			'birthDate'		=> $dateStart,
			'birthDate2'	=> $dateEnd
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
		for ($i = (PARTNER_HEIGHT_MIN + 1); $i < 221; $i++) {
			$items[$i] = $i;
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
		for ($i = (PARTNER_WEIGHT_MIN + 1); $i < 131; $i++) {
			$items[$i] = $i;
		}
		return $items;
	}

	/**
	 * make block <select>...</select>
	 * @param string $name
	 * @param string $className
	 * @param integer $value
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function BlockSelect($name, $className, $value)
	{
		if (!is_array($value)) {
			try {
				$unserValue = unserialize($value);
			} catch (\Exception $e) {
			}
		}

		$value		= isset($unserValue) && is_array($unserValue) ? $unserValue : $value;
		$className	= 'App\\Models\\' . $className;
		$items		= $className::select('*')->orderBy('name', 'asc')->get();
	
		foreach ($items as &$_item) {
			$_item->selected = is_array($value) ? (in_array($_item->id, $value) 	? 1 			: 0)
				: ($value == $_item->id 			? ' selected' 	: '');
		}
		return $items;
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
		foreach ($ar as $k => $v) {
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
	 * get text for top100update settings page
	 * @return string
	 */
	public function getTextTop100Update()
	{
		$text = '<p style="color:#f00">Вы не можете попасть в ТОП, т.к. не выполнено одно из условий</p>
						<p>Чтобы стать участником ТОПа нашего сайта, Вам необходимо выполнить <strong>всего 3 условия:</strong></p>
						<p>1. Иметь регистрацию на нашем сайте;</p>
						<p style="color:#f00">2. У вас должна быть загружена хотя бы одна фотография;</p>
						<p>3. Вам необходимо подтвердить желание участвовать в ТОПе.</p>
						<p><strong>Перейти в раздел <a class="name" href="' . route('registration.edit.photo') . '">Мои фото</a></strong></p>
						<p><br></p>';
		return session('textTop100') ?: $text;
	}

	/**
	 * get text for the form on the top100 settings page
	 * @return string
	 */
	public function getFormToTop()
	{
		$formToTop 	= '<form name="anketa" action="' . route('registration.top100.post') . '" method="post">' .
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
		$formToTop 	= '<form name="anketa" action="' . route('registration.top100.post') . '" method="post">' .
			csrf_field() . '
						<input type="submit" name="otsil" value="Попасть в ТОП" /></form>';
		return session('formToTop') ?: $formToTop;
	}

	/**
	 * out age type
	 * @param int $age
	 *
	 * @return string
	 */
	public function ageType(int $age)
	{
		$ageFin = substr($age, -1, 1);
		if (($age > 10 && $age < 20) || $ageFin <= 0) return "лет";
		if ($ageFin == 1) return "год";
		if ($ageFin >= 2 && $ageFin <= 4) return "года";
		return "лет";
	}

	/**
	 * Type age from to
	 * @param int $age
	 *
	 * @return string
	 */
	public function ageType2(int $age)
	{
		if ($age > 10 && $age < 20) return "лет";
		$ageFin = substr($age, -1, 1);
		if (($ageFin >= 2 && $ageFin <= 4) || $ageFin <= 0) return "лет";
		if ($ageFin == 1) return "года";
		return "лет";
	}

	/**
	 * Out to xml
	 * @param $obj
	 *
	 * @return void
	 */
	public function outToXml($obj)
	{
		$startStr = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
		return response()
			->view('xml.location', [
				'obj'		=> $obj,
				'startStr'		=> $startStr,
			])
			->header('Content-Type', 'text/xml')->send();
	}

	/**
	 * out diary type in cases
	 * @param integer $number
	 *
	 * @return string
	 */
	public function caseDiaryType(int $number)
	{
		if ($number > 10 && $number < 20) return "записей";
		$finalNumber = substr($number, -1, 1);
		if ($finalNumber <= 0) return "записей";
		if ($finalNumber == 1) return "запись";
		if ($finalNumber >= 2 && $finalNumber <= 4) return "записи";
		return "записей";
	}

	/**
	 * get data for search in DB
	 *
	 * @return string
	 */
	public function dataForDB()
	{
		$day 	= \Carbon\Carbon::now()->format('d');
		$month = \Carbon\Carbon::now()->format('m');
		return '____-' . $month . '-' . $day;
	}

	/**
	 * check the string and formating it from serialising to array
	 * @param string $str
	 * 
	 * @return string|array
	 */
	public function stringTransform($str)
	{
		if ($this->isSerialized($str)) {
			$str = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
			return unserialize($str);
		}
		return $str;
	}

	/**
	 * check the string is serialised or not
	 * @param string $str
	 * 
	 * @return bool
	 */
	function isSerialized($value): bool {
		return is_string($value) && preg_match('/^(a|s|i|b|O|C|N):/', $value);
	}
}