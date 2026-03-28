<?

namespace App\Services;

use App\Factories\HoroscopeFactory;
use App\Interfaces\HoroscopeInterface;
use App\Interfaces\HoroscopeTypeInterface;
use App\DTO\HoroscopeIndexPageDTO;

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
		$type = 1;
		$horoscope = $this->factory->make($type);
		return 
		new HoroscopeIndexPageDTO(
			horoscopes:	$this->repository->getByType($type),
			zodiak_text: $horoscope->getText(),
			horoscope_title: $horoscope->getTitle(),
			horoscopes_type: $this->typeRepository->getNotByType($type)
		);
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
	public function getTypeData(int $id): HoroscopeIndexPageDTO
	{
		$type = $id > 0 && $id <= 5 ? (int) $id : 1;
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