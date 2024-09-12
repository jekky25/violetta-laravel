<?
namespace App\Services;

class NameService
{
	protected $alphabet = [];
	const MEN = 'men';
	const WOMEN = 'women';
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct($alphabet)
	{
		$this->alphabet = $alphabet;
	}

	/**
	* get literals string of alphabet
	* @param string $sex
	*/
	public function getLiteralString($sex) : string
	{
		$this->prepare($sex);
		$str = '';

		foreach ($this->alphabet as $id => $liter) 
		{
			$str .= '<a href="' . route('names.subop', [$sex, $id]) . '">' . $liter . '</a>';
		}
		return $str;
	}

	/**
	* prepare an array of the alphabet
	* @param string $sex
	*/
	public function prepare($sex) : array
	{
		if ($sex == self::MEN) {
			unset ($this->alphabet[7]);
		}
		return $this->alphabet;
	}

	/**
	* add static links
	* @param int $id
	*/
	public function addLink($id) : string | bool
	{
		switch ($id) {
			case '8':
				return '<a href="http://www.russiamore.ru" class="name">Знакомства с иностранцами</a> - Хотите завязать романтические отношения, выйти замуж за иностранца, жить в другой
				стране? Тогда добро пожаловать на международный сайт знакомств Russiamore.';
				break;
			case '9':
				return '<a href="http://www.lovevolna.ru" style="padding:0px;" class="name">Служба знакомств на сайте</a>. Приглашаем мужчин и женщин на наши популярные знакомства, ведь именно у нас вы можете общаться и
				знакомиться быстро и бесплатно!';	
				break;
		}
		return false;
	}

}