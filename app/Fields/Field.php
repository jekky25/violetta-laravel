<?php
declare(strict_types=1);

namespace App\Fields;

use App\Repositories\BodyRepository;
use App\Repositories\EyesRepository;
use App\Repositories\HairTypeRepository;
use App\Interfaces\CountryInterface;
use App\Services\FormatService;
use App\Services\DataService;
use Illuminate\Support\Facades\Auth;

/**
 * Class Field
 */
abstract class Field
{
	private const MAN	= MEN;
	private const WOMAN	= WOMEN;
	public $names		= [];
	private $fields		= [];
	protected static $user = null;

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
	* get an authorized user or null
	* @return Auth|null
	*/
	public function user() {
		if (self::$user === null) return Auth::user();
		return self::$user;
	}

	/**
	* check property on null
	* @return bool
	*/
	protected function check($val) {
		return $val !== null ? $val : 0;
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

	public function body($val = null) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->check($val) ? $this->format->BlockSelect(BODY_CLASS, $val) : BodyRepository::getAll(); 
	}

	public function eyes($val = null) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->check($val) ? $this->format->BlockSelect(EYES_CLASS, $val) : EyesRepository::getAll();
	}

	public function hairType($val = null) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->check($val) ? $this->format->BlockSelect(HAIR_TYPE_CLASS, $val) : HairTypeRepository::getAll();
	}

	public function country() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->country->getAll();
	}

	public function region(int $countryId = 0) :\Illuminate\Database\Eloquent\Collection|array
	{
		return $countryId > 0 ? $this->region->getByCountryId($countryId) : [];
	}

	public function city(int $regionId = 0) :\Illuminate\Database\Eloquent\Collection|array
	{
		return $regionId > 0 ? $this->city->getByRegionId($regionId) : [];
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