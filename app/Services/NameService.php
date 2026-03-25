<?php
namespace App\Services;

use App\DTO\GenderPageDTO;
use App\DTO\NamePageDTO;
use App\Interfaces\NameInterface;
use App\Enums\Sex;

class NameService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(private NameInterface $repository) {}

	/**
	* get alphabet width indexes
	*/
	public function alphabet(): array
	{
		return config('names.alphabet');
	}

	/**
	* get a group of the names
	*/
	public function getGroupedNames(): array
	{
		$namesGroupF = $this->repository->getPartsByIds(array_keys($this->alphabet()), 'f');
		$namesGroupM = $this->repository->getPartsByIds(array_keys($this->alphabet()), 'm');

		for ($i = 1; $i <= count($this->alphabet()); $i++)
		{
			$names['m'][$i] = !empty($namesGroupM[$i]) ? $namesGroupM[$i] : collect();
			$names['f'][$i] = !empty($namesGroupF[$i]) ? $namesGroupF[$i] : collect();
		}
		return $names;
	}

	/**
	* get data for the gender page
	*/
	public function getGenderPageData(string $sex, int $id = 1): GenderPageDTO
	{
		$sexEnum = Sex::fromView($sex);
		return new GenderPageDTO(
			sex: $sex,
			alphabet: $this->alphabet(),
			names: $this->repository->getAllbySex($sexEnum->value, $id),
			title: $sex == Sex::MEN->value ? 'Значение мужского имени' : 'Значение женского имени'
		);
	}

	/**
	* get data for the name page
	*/
	public function getNamePageData(int $id): NamePageDTO
	{
		$name = $this->repository->getById($id);
		$sexEnum = Sex::from($name->gender);
		return new NamePageDTO(
            name: $name,
            title: 'Значение имени ' . $name->name,
            alphabet: $this->alphabet(),
			text: nl2br($name->description),
            sex: $sexEnum->toView(),
            genderTitle: $name->gender === Sex::MALE->value
                ? 'Мужские имена по алфавиту'
                : 'Женские имена по алфавиту'
        );
	}
}