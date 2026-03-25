<?
namespace App\Services;

use App\Interfaces\NameInterface;
use Illuminate\Support\Collection;

class NameService
{
	protected $alphabet = [];
	
	const MALE = 'm';
	const FEMALE = 'f';
	const MEN = 'men';
	const WOMEN = 'women';

	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(private NameInterface $nameRepository)
	{
		$this->alphabet = config('names.alphabet');
	}

	/**
	* get literals string of alphabet
	*/
	public function getLiteralString(string $sex): string
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
	*/
	public function prepare(string $sex): array
	{
		if ($sex == self::MEN) {
			unset($this->alphabet[7]);
		}
		return $this->alphabet;
	}

	/**
	* add static links
	*/
	public function addLink($id): string | bool
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

	/**
	* get alphabet width indexes
	*/
	public function getAlphabet(): array
	{
		return $this->alphabet;
	}

	/**
	* get a group of the names
	*/
	public function getGroupedNames(): array
	{
		$namesGroupF = $this->nameRepository->getPartsByIds(array_keys($this->alphabet), 'f');
		$namesGroupM = $this->nameRepository->getPartsByIds(array_keys($this->alphabet), 'm');

		for ($i = 1; $i <= count($this->alphabet); $i++)
		{
			$names['m'][$i] = !empty($namesGroupM[$i]) ? $namesGroupM[$i] : new Collection;
			$names['f'][$i] = !empty($namesGroupF[$i]) ? $namesGroupF[$i] : new Collection;
		}
		return $names;
	}

	/**
	* get data for the gender page
	*/
	public function getGenderPageData(string $sex, int $id = 1): array
	{
		return 
		[
			'sex'				=> $sex,
			'alphabet'			=> $this->getAlphabet(),
			'names'				=> $this->nameRepository->getAllbySex($sex == self::WOMEN ? self::MALE	: self::FEMALE, $id),
			'nameTitle'			=> $sex == self::MEN ? 'Значение мужского имени' : 'Значение женского имени',
			'namesGender'		=> $this->getLiteralString($sex)
		];
	}

	/**
	* get data for the name page
	*/
	public function getNamePageData(int $id): array
	{
		$name = $this->nameRepository->getById($id);
		return
		[
			'name'				=> $name,
			'nameTitle'			=> 'Значение имени ' . $name->name,
			'alphabet'			=> $this->getAlphabet(),
			'nameText'			=> str_replace("\n","<br /><br />\n",$name->description),
			'namesGender'		=> $this->getLiteralString($name->gender == self::MALE ? self::MEN	: self::WOMEN),
			'nameTitleGender'	=> $name->gender == self::MALE ? 'Мужские имена по алфавиту' 	: 'Женские имена по алфавиту',
			'bannerNames'		=> $this->addLink($id)
		];
	}
}