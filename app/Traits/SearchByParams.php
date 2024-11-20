<?php

namespace App\Traits;

use App\Services\DataService;

trait SearchByParams {

	/***********************************
     * SET WHERE BY PARAMS
     ***********************************/
	private function getBySex($findSex, $sex)
	{
		$data = new DataService;
		if ($findSex !== 0 && $sex == 0) $this->ankets->where('user_sex', $findSex);
		else if ($findSex == 0 && $sex !== 0) 
		{
			if ($sex == MEN) 
			{
				$this->ankets->where(function ($query) {
					$data->queryBlock([MEN, GOMOSEXUAL, BISEXUAL], $query);
					$data->queryBlockOr([WOMEN, GETEROSEXUAL, BISEXUAL], $query);
				});
			} else if ($sex == WOMEN) 
			{
				$this->ankets->where(function ($query) {
					$data->queryBlock([WOMEN, GOMOSEXUAL, BISEXUAL], $query);
					$data->queryBlockOr([MEN, GETEROSEXUAL, BISEXUAL], $query);
				});
			}
		} else if ($findSex !== 0 && $sex !== 0) 
		{
			if ($sex == MEN) 
			{
				if ($findSex == MEN) 
				{
					$data->queryBlock([MEN, GOMOSEXUAL, BISEXUAL], $this->ankets);
				} else if ($findSex == WOMEN) 
				{
					$data->queryBlock([WOMEN, GETEROSEXUAL, BISEXUAL], $this->ankets);
				}
			} else if ($sex == WOMEN) 
			{
				if ($findSex == WOMEN) 
				{
					$data->queryBlock([WOMEN, GOMOSEXUAL, BISEXUAL], $this->ankets);
				} else if ($findSex == MEN) 
				{
					$data->queryBlock([MEN, GETEROSEXUAL, BISEXUAL], $this->ankets);
				}
			}
		}
		return $this->ankets;
	}

	private function getByPhoto($photo) 
	{
		if (empty($photo)) return $this->ankets;
		return $this->ankets->where('user_fotos', '>', 0);
	}

	private function getByAgeMin($ageMin)
	{
		if ($ageMin <= AGE_MIN) return $this->ankets;
		return $this->ankets->where('user_birth_date', '<', (new DataService)->birthAround($ageMin-1));
	}

	private function getByAgeMax($ageMax)
	{
		if ($ageMax <= AGE_MAX) return $this->ankets;
		return $this->ankets->where('user_birth_date', '>', (new DataService)->birthAround($ageMax));
	}

	private function getByHeightMin($heightMin)
	{
		if ($heightMin <= HEIGHT_MIN) return $this->ankets;
		return $this->ankets->where('user_height', '>=', $heightMin);
	}

	private function getByHeightMax($heightMax)
	{
		if ($heightMax <= HEIGHT_MAX) return $this->ankets;
		return $this->ankets->where('user_height', '<=', $heightMax);
	}

	private function getByWeightMin($weightMin)
	{
		if ($weightMin <= WEIGHT_MIN) return $this->ankets;
		return $this->ankets->where('user_weight', '>=', $weightMin);
	}

	private function getByWeightMax($weightMax)
	{
		if ($weightMax <= WEIGHT_MAX) return $this->ankets;
		return $this->ankets->where('user_weight', '<=', $weightMax);
	}

	private function getByBody($body)
	{
		if (empty($body)) return $this->ankets;
		return $this->ankets->where('user_body', $body);
	}

	private function getByHairType($hairType)
	{
		if (empty($hairType)) return $this->ankets;
		return $this->ankets->where('user_hair_type', $hairType);
	}

	private function getByEyes($eyes)
	{
		if (empty($eyes)) return $this->ankets;
		return $this->ankets->where('user_eyes', $eyes);
	}

	private function getByCountry($country)
	{
		if (empty($country)) return $this->ankets;
		return $this->ankets->where('user_country', $country);
	}

	private function getByRegion($region)
	{
		if (empty($region)) return $this->ankets;
		return $this->ankets->where('user_region', $region);
	}

	private function getByCity($city)
	{
		if (empty($city)) return $this->ankets;
		return $this->ankets->where('user_city', $city);
	}
}