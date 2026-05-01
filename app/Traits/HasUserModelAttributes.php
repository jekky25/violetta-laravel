<?php
declare(strict_types=1);

namespace App\Traits;

use App\Repositories\UserRepository;
use App\Services\FormatService;
use App\Models\Photo;
use App\Repositories\SpeakLangRepository;
use App\Repositories\EducationRepository;
use App\Repositories\SmokeRepository;
use App\Repositories\AlcoholRepository;
use App\Repositories\BodyRepository;
use App\Repositories\MeetTargetRepository;
use App\Repositories\InterestRepository;
use App\Repositories\SexOrientRepository;

/**
 * Trait HasUserModelAttributes
 *
 */
trait HasUserModelAttributes
{
	public function getAgeAttribute()
	{
		return $this->data->age($this->birth_date);
	}

	public function getAgeTypeAttribute()
	{
		return (new formatService)->ageType((int)$this->age);
	}

	public function getBirthDayAttribute()
	{
		return $this->data->selectFromDate($this->birth_date, DATE_DAY);
	}

	public function getBirthMonthAttribute()
	{
		return $this->data->selectFromDate($this->birth_date, DATE_MONTH);
	}

	public function getBirthYearAttribute()
	{
		return $this->data->selectFromDate($this->birth_date, DATE_YEAR);
	}

	public function getAgeStrAttribute()
	{
		return $this->age . ' ' . $this->age_type;
	}

	public function getFindSexOrientAttribute()
	{
		$findSOrient = '';
		if ($this->sex_orient == GOMOSEXUAL)
			$findSOrient .= $this->sex == MEN ? 'парня' : 'девушку';
		elseif ($this->sex_orient == BISEXUAL)
			$findSOrient .= $this->sex == MEN ? 'девушку или парня' : 'парня или девушку';
		else
			$findSOrient .= $this->sex == MEN ? 'девушку' : 'парня';

		if ($this->partner_age_min > PARTNER_AGE_MIN && $this->partner_age_max > PARTNER_AGE_MAX) {
			$findSOrient .= ' ' . $this->partner_age_min . '-' . $this->partner_age_max;
			$findSOrient .= ' ' . (new formatService)->ageType($this->partner_age_max);
		} elseif ($this->partner_age_min > PARTNER_AGE_MIN && $this->partner_age_max <= PARTNER_AGE_MAX) {
			$findSOrient .= ' от ' . $this->partner_age_min;
			$findSOrient .= ' ' . (new formatService)->ageType2($this->partner_age_min);
		} elseif ($this->partner_age_min <= PARTNER_AGE_MIN && $this->partner_age_max > PARTNER_AGE_MAX) {
			$findSOrient .= ' до ' . $this->partner_age_max;
			$findSOrient .= ' ' . (new formatService)->ageType2($this->partner_age_max);
		}
		return $findSOrient;
	}

	public function getZodiacAttribute()
	{
		return $this->data->zodiac($this->birth_date);
	}

	public function getNumberDiaryAttribute()
	{
		return count($this->diary);
	}

	public function getNumberDiaryStrAttribute()
	{
		return $this->number_diary . ' ' . (new formatService)->caseDiaryType($this->number_diary);
	}

	public function getRatingStrAttribute()
	{
		$maxRate = (new UserRepository())->getMaxRating($this->sex);
		return (new formatService)->rating($this->rating, $maxRate);
	}

	public function getDescriptionAttribute($val)
	{
		$val = stripslashes($val);
		return str_replace("\n", "\n<br />\n", $val);
	}

	public function getSexStrAttribute()
	{
		return $this->sex == MEN ? 'Мужской' : 'Женский';
	}

	public function getDateMakeStrAttribute()
	{
		return $this->data->dateFormat($this->make_date);
	}


	public function getDateRefreshAttribute($val)
	{
		return $this->make_date !== $this->refresh_date ? $this->data->dateFormat($this->refresh_date) : $val;
	}

	public function getSpeakLangAttribute($val)
	{
		return unserialize($val);
	}

	public function getICQAttribute($val)
	{
		return (int)$val > 0 ? $val : '';
	}

	public function getPartnerDescriptionAttribute(?string $val): string
	{
		if ($val === null) return '';
		$val = stripslashes(trim($val));
		return str_replace("\n", "\n<br />\n", $val);
	}

	public function getPhotoIdAttribute()
	{
		if (empty($this->photo)) return null;
		return !empty($this->photo->id) ? $this->photo->id : null;
	}

	public function getFirstPhotoAttribute()
	{
		if ($this->photo->count() == 0) return null;

		return $this->photo instanceof Photo ? $this->photo : $this->photo[0];
	}

	public function getClassAAttribute()
	{
		return $this->sex == MEN ? 'name_man' : 'name_woman';
	}

	public function getNameClassAttribute()
	{
		return  $this->sex == MEN ? 'name_man' : 'name_woman';
	}

	public function getOnTopAttribute()
	{
		return '<strong>' . ($this->sex == WOMEN ? 'поднялась' : 'поднялся') . '</strong>: ' . $this->data->lastVisit($this->top100);
	}

	public function getPartnerSexAttribute()
	{
		if ($this->sex_orient == self::SEX_BISEXUAL || $this->sex_orient == self::SEX_TRANS) {
			$partnerSex = 'Мужской, Женский';
		} elseif ($this->sex_orient == self::SEX_HETERO) {
			$partnerSex = $this->sex == MEN ? 'Женский' : 'Мужской';
		} else {
			$partnerSex = $this->sex == WOMEN ? 'Женский' : 'Мужской';
		}
		return $partnerSex;
	}

	public function getPartnerAgeMinAttribute($val)
	{
		return !empty($val) ? (int)$val : self::AGE_MIN;
	}

	public function getPartnerAgeMaxAttribute($val)
	{
		return !empty($val) ? (int)$val : self::AGE_MIN;
	}

	public function getPartnerHeightMinAttribute($val)
	{
		return !empty($val) ? (int)$val : self::HEIGHT_MIN;
	}

	public function getPartnerHeightMaxAttribute($val)
	{
		return !empty($val) ? (int)$val : self::HEIGHT_MIN;
	}

	public function getPartnerWeightMinAttribute($val)
	{
		return !empty($val) ? (int)$val : self::WEIGHT_MIN;
	}

	public function getPartnerWeightMaxAttribute($val)
	{
		return !empty($val) ? (int)$val : self::WEIGHT_MIN;
	}

	public function getPartnerAgeAttribute()
	{
		if (!($this->partner_age_min > PARTNER_AGE_MIN || $this->partner_age_max > PARTNER_AGE_MAX)) return null;
		if ($this->partner_age_min > PARTNER_AGE_MIN && $this->partner_age_max > PARTNER_AGE_MAX)
			return ' ' . $this->partner_age_min . '-' . $this->partner_age_max . ' ' . (new formatService)->ageType($this->partner_age_max);
		if ($this->partner_age_min > PARTNER_AGE_MIN && $this->partner_age_max <= PARTNER_AGE_MAX)
			return ' от ' . $this->partner_age_min . ' ' . (new formatService)->ageType2($this->partner_age_min);
		return ' до ' . $this->partner_age_max . ' ' . (new formatService)->ageType2($this->partner_age_max);
	}

	public function getPartnerHeightAttribute()
	{
		if (!($this->partner_height_min > PARTNER_HEIGHT_MIN || $this->partner_height_max > PARTNER_HEIGHT_MAX)) return null;
		if ($this->partner_height_min > PARTNER_HEIGHT_MIN && $this->partner_height_max > PARTNER_HEIGHT_MAX)
			return ' ' . $this->partner_height_min . '-' . $this->partner_height_max . ' см';
		if ($this->partner_height_min > PARTNER_HEIGHT_MIN && $this->partner_height_max <= PARTNER_HEIGHT_MAX)
			return ' от ' . $this->partner_height_min . ' см';
		return	' до ' . $this->partner_height_max . 'см';
	}

	public function getPartnerWeightAttribute()
	{
		if (!($this->partner_weight_min > PARTNER_WEIGHT_MIN || $this->partner_weight_max > PARTNER_WEIGHT_MAX))  return null;
		if ($this->partner_weight_min > PARTNER_WEIGHT_MIN && $this->partner_weight_max > PARTNER_WEIGHT_MAX)
			return ' ' . $this->partner_weight_min . '-' . $this->partner_weight_max . ' кг';
		if ($this->partner_weight_min > PARTNER_WEIGHT_MIN && $this->partner_weight_max <= PARTNER_WEIGHT_MAX)
			return ' от ' . $this->partner_weight_min . ' кг';
		return ' до ' . $this->partner_weight_max . 'кг';
	}

	public function getMonthVisitsNewAttribute()
	{
		return $this->anketVisitRepository->visitsNew($this)->count();
	}

	public function getMonthVisitsAttribute()
	{
		return count($this->visits);
	}

	public function getLastvisitFormatAttribute()
	{
		return $this->data->getDate($this->lastvisit);
	}

	public function getIsAboutPartnerAttribute()
	{
		foreach ($this->fieldsAboutPartner as $prop) {
			if (!empty($this->$prop)) return true;
		}
		return false;
	}

	public function getPartnerLanguagesStrAttribute()
	{
		return $this->getPropertyFew(SpeakLangRepository::class, $this->partner_languages);
	}

	public function getPartnerEducationStrAttribute()
	{
		return $this->getPropertyFew(EducationRepository::class, $this->partner_education);
	}

	public function getPartnerSmokeStrAttribute()
	{
		return $this->getPropertyFew(SmokeRepository::class, $this->partner_smoke);
	}

	public function getPartnerAlcoholStrAttribute()
	{
		return $this->getPropertyFew(AlcoholRepository::class, $this->partner_alcohol);
	}

	public function getPartnerBodyStrAttribute()
	{
	
		return $this->getPropertyFew(BodyRepository::class, $this->partner_body);
	}

	public function getSpeakLangStrAttribute()
	{
		return $this->getPropertyFew(SpeakLangRepository::class, $this->speak_lang);
	}

	public function getTargetsStrAttribute()
	{
		return $this->getPropertyFew(MeetTargetRepository::class,	$this->targets);
	}

	public function getInterestsStrAttribute()
	{
		return $this->getPropertyFew(InterestRepository::class, $this->interests);
	}

	public function getSexOrientStrAttribute()
	{
		return $this->getPropertyFew(SexOrientRepository::class, $this->sex_orient);
	}

	public function setSexOrientAttribute($val)
	{
		$this->attributes['sex_orient'] = $val < self::SEX_BISEXUAL || $val > self::SEX_TRANS ? self::SEX_HETERO : $val;
	}

	public function setHeightAttribute($val)
	{
		$this->attributes['height'] = $val < (self::HEIGHT_MIN + 1)	? self::HEIGHT_MIN	: $val;
	}

	public function setWeightAttribute($val)
	{
		$this->attributes['weight'] = $val < (self::WEIGHT_MIN + 1)	? self::WEIGHT_MIN	: $val;
	}

	public function setTargetsAttribute($val)
	{
		$this->attributes['targets'] = $this->createData()->serializeInput($val);
	}

	public function setSpeakLangAttribute($val)
	{
		$this->attributes['speak_lang'] = $this->createData()->serializeInput($val);
	}

	public function setInterestsAttribute($val)
	{
		$this->attributes['interests'] = $this->createData()->serializeInput($val);
	}

	public function setIcqAttribute($val)
	{
		$this->attributes['icq'] = (string)$val;
	}

	public function setUrlAttribute(?string $val)
	{
		$this->attributes['url'] = $val ? addslashes($val) : '';
	}

	public function setPhoneAttribute(?string $val)
	{
		$this->attributes['phone'] = $val ? addslashes($val) : '';
	}

	public function setDescriptionAttribute($val)
	{
		$this->attributes['description'] = addslashes($val);
	}

	public function setPartnerBodyAttribute($val)
	{
		$this->attributes['partner_body'] = $this->createData()->serializeInput($val);
	}

	public function setPartnerLanguagesAttribute($val)
	{
		$this->attributes['partner_languages'] = $this->createData()->serializeInput($val);
	}

	public function setPartnerAlcoholAttribute($val)
	{
		$this->attributes['partner_alcohol'] = $this->createData()->serializeInput($val);
	}

	public function setPartnerSmokeAttribute($val)
	{
		$this->attributes['partner_smoke'] = $this->createData()->serializeInput($val);
	}

	public function setPartnerEducationAttribute($val)
	{
		$this->attributes['partner_education'] = $this->createData()->serializeInput($val);
	}
}