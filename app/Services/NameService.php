<?php
namespace App\Services;

use App\Interfaces\NameInterface;

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
	* map sex for DB request
	*/
	private function mapSexToDb(string $sex): string
	{
    	return $sex === self::WOMEN ? self::FEMALE : self::MALE;
	}

	/**
	* map sex for view
	*/
	private function mapSexToView(string $sex): string
	{
    	return $sex === self::MALE ? self::MEN : self::WOMEN;
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
			$names['m'][$i] = !empty($namesGroupM[$i]) ? $namesGroupM[$i] : collect();
			$names['f'][$i] = !empty($namesGroupF[$i]) ? $namesGroupF[$i] : collect();
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
			'names'				=> $this->nameRepository->getAllbySex($this->mapSexToDb($sex), $id),
			'nameTitle'			=> $sex == self::MEN ? 'Значение мужского имени' : 'Значение женского имени'
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
			'sex'				=> $this->mapSexToView($name->gender),
			'name'				=> $name,
			'nameTitle'			=> 'Значение имени ' . $name->name,
			'alphabet'			=> $this->getAlphabet(),
			'nameText'			=> str_replace("\n","<br /><br />\n",$name->description),
			'nameTitleGender'	=> $name->gender == self::MALE ? 'Мужские имена по алфавиту' 	: 'Женские имена по алфавиту'
		];
	}
}