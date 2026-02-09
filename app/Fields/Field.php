<?php
declare(strict_types=1);

namespace App\Fields;

use App\Repositories\BodyRepository;
use App\Repositories\EyesRepository;
use App\Repositories\HairTypeRepository;
use App\Interfaces\CountryInterface;
use App\Services\FormatService;
use App\Services\DataService;

/**
 * Class Field
 */
abstract class Field
{
	private const MAN	= MEN;
	private const WOMAN	= WOMEN;
	public $names		= [];
	private $fields		= [];

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected CountryInterface $country,
		protected DataService $data,
		protected FormatService $format

	)
	{
	}

	/**
	* get fields by field name
	* @return array
	*/
	public function get() :array
	{
		foreach ($this->names as $name) {
			$this->fields[$name] = $this->{$name}();
		}
		return $this->fields;
    }

	public function body() :\Illuminate\Database\Eloquent\Collection
	{
		return BodyRepository::getAll();
	}


	public function eyes() :\Illuminate\Database\Eloquent\Collection
	{
		return EyesRepository::getAll();
	}

	public function hairType() :\Illuminate\Database\Eloquent\Collection
	{
		return HairTypeRepository::getAll();
	}

	public function country() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->country->getAll();
	}

	public function age() :array
	{
		return $this->data->getAges();
	}

	public function height() :array
	{
		return $this->format->getHeights();
	}

	public function weight() :array
	{
		return $this->format->getWeights();
	}

	public function sex() :\Illuminate\Support\Collection
	{
		$ar = [
			(object)['id' => self::MAN, 'name' => 'мужчина'],
			(object)['id' => self::WOMAN, 'name' => 'женщина']
			];
		return collect($ar);
	}

	public function findSex() :\Illuminate\Support\Collection
	{
		$ar = [
			(object)['id' => self::MAN, 'name' => 'мужчину'],
			(object)['id' => self::WOMAN, 'name' => 'женщину']
			];
		return collect($ar);
	}

	public function perPage() :\Illuminate\Support\Collection
	{
		$ar = [
			(object)['id' => 20,	'name' => 20, 'selected' => true],
			(object)['id' => 40,	'name' => 40],
			(object)['id' => 60,	'name' => 60]
		];
		return collect($ar);
	}
}