<?
namespace App\Services;

use App\Repositories\CountryRepository;
use App\Repositories\RegionRepository;
use App\Repositories\CityRepository;
use App\Repositories\BodyRepository;
use App\Repositories\HairTypeRepository;
use App\Repositories\EyesRepository;
use App\Services\FormatService;

class SearchService
{
	protected $critsSearch		= null;
	protected $critSex			= null;
	protected $critAgeMin		= null;
	protected $critAgeMax		= null;
	protected $critHeightMin	= null;
	protected $critHeightMax	= null;
	protected $critWeightMin	= null;
	protected $critWeightMax	= null;
	protected $critBody			= null;
	protected $critHairType		= null;
	protected $critEyes			= null;
	protected $critCountry		= null;
	protected $critRegion		= null;
	protected $critCity			= null;
	protected $critPhoto		= null;
	protected $critOnline		= null;
	protected $countryRepository;
	protected $cityRepository;
	protected $regionRepository;
	protected $formatService;
	protected $ankets;
	protected $params;

	/**
	* get the description of the searching parameters
	* @param  \Illuminate\Database\Eloquent\Collection $ankets 
	* @param  array $params
	* @return string
	*/
	public function getSearchText($ankets, $params)
	{
		$this->countryRepository	= new CountryRepository();
		$this->regionRepository		= new RegionRepository();
		$this->cityRepository		= new CityRepository();
		$this->formatService		= new FormatService();
		$this->ankets = $ankets;
		$this->params = $params;
		$this->setTxtBySex($this->params['find_sex'], $this->params['sex']);
		$this->setTxtAgeMin();
		$this->setTxtAgeMax();
		$this->setTxtHeightMin();
		$this->setTxtHeightMax();
		$this->setTxtWeightMin();
		$this->setTxtWeightMax();
		$this->setTxtBody();
		$this->setTxtHairType();
		$this->setTxtEyes();
		$this->setTxtPhoto();
		$this->setTxtOnline();
		$this->setTxtCountry();
		$this->setTxtRegion();
		$this->setTxtCity();
		$this->setTxtFinally();
		return $this->critsSearch;
	}

    /***********************************
     * SET TEXT PARAMS
     ***********************************/

	private function setTxtBySex($findSex, $sex)
	{
		if ($findSex !== 0 && $sex == 0)	$this->critSex = $findSex == MEN 	? '<strong>мужчину</strong>' 				: '<strong>женщину</strong>';
		if ($findSex == 0 && $sex !== 0)	$this->critSex = $sex == MEN 		? 'тех, кто ищет <strong>мужчину</strong>' 	: 'тех, кто ищет <strong>женщину</strong>';
		if ($findSex !== 0 && $sex !== 0) 
		{
			$this->critSex = $sex == MEN ? 
			($findSex == MEN 	?	'<strong>мужчину</strong>, который ищет <strong>мужчину</strong>'	:	 '<strong>женщину</strong>, которая ищет <strong>мужчину</strong>') :
			($findSex == WOMEN	?	'<strong>женщину</strong>, которая ищет <strong>женщину</strong>'	:	'<strong>мужчину</strong>, который ищет <strong>женщину</strong>');
		}
	}

	private function setTxtAgeMin()
	{
		$this->critAgeMin = $this->params['age_min'] > AGE_MIN ? ' от <strong>' . $this->params['age_min'] . '</strong> ' . $this->formatService->ageType2($this->params['age_min']) : null;
	}

	private function setTxtAgeMax()
	{
		$this->critAgeMax = $this->params['age_max'] > AGE_MAX ? ' до <strong>' . $this->params['age_max'] . '</strong> ' . $this->formatService->ageType2($this->params['age_max']) : null;
	}

	private function setTxtHeightMin()
	{
		$this->critHeightMin = $this->params['height_min'] > HEIGHT_MIN ? ' от <strong>' . $this->params['height_min'] . '</strong> см' : null;
	}

	private function setTxtHeightMax()
	{
		$this->critHeightMax = $this->params['height_max'] > HEIGHT_MAX ? ' до <strong>' . $this->params['height_max'] . '</strong> см' : null;
	}

	private function setTxtWeightMin()
	{
		$this->critWeightMin = $this->params['weight_min'] > WEIGHT_MIN ? ' от <strong>' . $this->params['weight_min'] . '</strong> кг' : null;
	}

	private function setTxtWeightMax()
	{
		$this->critWeightMax = $this->params['weight_max'] > WEIGHT_MAX ? ' до <strong>' . $this->params['weight_max'] . '</strong> кг' : null;
	}

	private function setTxtBody()
	{
		if (empty($this->params['body'])) return false;
		$oBody = BodyRepository::getById ($this->params['body']);
		$this->critBody = '<br /> телосложение <strong>' . $oBody->name . '</strong>';
	}

	private function setTxtHairType()
	{
		if (empty($this->params['hair_type'])) return false;
		$oHairType = HairTypeRepository::getById ($this->params['hair_type']);
		$this->critHairType = '<br /> тип волос <strong>' . $oHairType->name . '</strong>';
	}
	
	private function setTxtEyes()
	{
		if (empty($this->params['eyes'])) return false;
		$oEyes = EyesRepository::getById ($this->params['eyes']);
		$this->critEyes = '<br /> глаза <strong>' . $oEyes->name . '</strong>';
	}

	private function setTxtCountry() 
	{
		if (empty($this->params['country'])) return false;
		$oCountry = $this->countryRepository->getById ($this->params['country']);
		$this->critCountry = $this->params['city'] > 0 || $this->params['region'] > 0 ? ' (<strong>' . $oCountry->name . ')</strong>' : '<br /> из <strong>' . $oCountry->name . '</strong>';
	}

	private function setTxtRegion() 
	{
		if (empty($this->params['region'])) return false;
		$oRegion = $this->regionRepository->getById ($this->params['region']);
		$this->critRegion = '<br /> из <strong>' . $oRegion->name . '</strong>';
	}

	private function setTxtCity() 
	{
		if (empty($this->params['city'])) return false;
		$oCity = $this->cityRepository->getById ($this->params['city']);
		$this->critCity = ' г. <strong>' . $oCity->name . '</strong>';
		$this->critCity = empty($this->params['region']) ? ' из' . $this->critCity : $this->critCity;
	}

	private function setTxtPhoto() 
	{
		if (empty($this->params['photo'])) return false;
		$this->critPhoto = '<br />только <strong>с фото</strong>';
	}

	private function setTxtOnline() 
	{
		if (empty($this->params['online'])) return false;
		$this->critOnline = '<br />сейчас <strong>на сайте</strong>';
	}

	/**
	* check if params were inputed
	* @return bool
	*/
	private function isCrits()
	{
		if (!empty($this->critSex)) return true;
		if (!empty($this->critPhoto)) return true;
		if (!empty($this->critOnline)) return true;
		if (!empty($this->critAgeMin)) return true;
		if (!empty($this->critAgeMax)) return true;
		if (!empty($this->critHeightMin)) return true;
		if (!empty($this->critHeightMax)) return true;
		if (!empty($this->critWeightMin)) return true;
		if (!empty($this->critWeightMax)) return true;
		if (!empty($this->critBody)) return true;
		if (!empty($this->critHairType)) return true;
		if (!empty($this->critEyes)) return true;
		if (!empty($this->critCountry)) return true;
		if (!empty($this->critRegion)) return true;
		if (!empty($this->critCity)) return true;
		return false;
	}

	/**
	* make the full text
	* @return void
	*/
	private function setTxtFinally()
	{
		if (!$this->isCrits()) return null;
		$this->critsSearch = 'Вы ищете: ';
		$this->critsSearch .= !empty ($this->critSex)										? $this->critSex							: '<strong>мужчину</strong> или <strong>женщину</strong>';
		$this->critsSearch .= !empty ($this->critAgeMin)									? $this->critAgeMin							: '';
		$this->critsSearch .= !empty ($this->critAgeMax)									? $this->critAgeMax							: '';
		$this->critsSearch .= !empty($this->critHeightMin) || !empty($this->critHeightMax)	? '<br /> рост '							: '';
		$this->critsSearch .= !empty($this->critHeightMin)									? $this->critHeightMin						: '';
		$this->critsSearch .= !empty($this->critHeightMax)									? $this->critHeightMax						: '';
		$this->critsSearch .= !empty($this->critWeightMin) || !empty($this->critWeightMax)	? '<br />вес '								: '';
		$this->critsSearch .= !empty($this->critWeightMin)									? $this->critWeightMin						: '';
		$this->critsSearch .= !empty($this->critWeightMax)									? $this->critWeightMax						: '';
		$this->critsSearch .= !empty($this->critBody)										? $this->critBody 							: '';
		$this->critsSearch .= !empty($this->critHairType)									? $this->critHairType						: '';
		$this->critsSearch .= !empty($this->critEyes)										? $this->critEyes 							: '';
		$this->critsSearch .= !empty($this->critCountry)									? $this->critCountry						: '';
		$this->critsSearch .= !empty($this->critRegion)										? $this->critRegion							: '';
		$this->critsSearch .= !empty($this->critCity)										? $this->critCity							: '';
		$this->critsSearch .= !empty($this->critPhoto)										? $this->critPhoto							: '';
		$this->critsSearch .= !empty($this->critOnline)										? $this->critOnline							: '';
	}
}