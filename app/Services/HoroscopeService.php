<?php

namespace App\Services;

use App\Factories\HoroscopeFactory;
use App\Interfaces\HoroscopeInterface;
use App\Interfaces\HoroscopeTypeInterface;
use App\DTO\HoroscopeIndexPageDTO;
use App\Enums\HoroscopeType;

class HoroscopeService
{
	public function __construct(
		private HoroscopeInterface $repository,
		private HoroscopeTypeInterface $typeRepository,
		private HoroscopeFactory $factory
	) {}

	/**
	* get data for the index page
	*/
	public function getIndexData(): HoroscopeIndexPageDTO
	{
		return $this->buildByType(HoroscopeType::DEFAULT);
	}

	/**
	* get data for the item page
	*/
	public function getItemData(int $id): HoroscopeIndexPageDTO
	{
		$horoscope	= $this->repository->getById($id);
		$type = $horoscope->type;
		return 
		new HoroscopeIndexPageDTO(
			horoscopes: $this->repository->getByType($type),
			zodiak_text: $horoscope->description,
			horoscope_title: $horoscope->name,
			horoscopes_type: $this->typeRepository->getNotByType($type)
		);
	}

	/**
	* get data for the type page
	*/
	public function getTypeData(int $type): HoroscopeIndexPageDTO
	{
		return $this->buildByType($type);
	}

	private function buildByType(int $type): HoroscopeIndexPageDTO
	{
		$type = HoroscopeType::toView($type);
		$horoscope = $this->factory->make($type);
		return
		new HoroscopeIndexPageDTO(
			horoscopes: $this->repository->getByType($type),
			zodiak_text: $horoscope->getText(),
			horoscope_title: $horoscope->getTitle(),
			horoscopes_type: $this->typeRepository->getNotByType($type)
		);
	}
}