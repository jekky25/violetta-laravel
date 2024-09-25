<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Repositories\UserRepository;
use App\Helpers\Helper;
use App\Models\Photo;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	* The attributes that are mass assignable.
	*
	* @var array<int, string>
	*/
	protected $fillable = [
		'name',
		'email',
		'password',
		'user_login',
		'user_password',
		'user_hash',
		'user_mail',
		'user_sex',
		'user_fotos',
		'user_reiting',
		'user_name',
		'user_birth_date',
		'user_country',
		'user_region',
		'user_city',
		'user_height',
		'user_weight',
		'user_body',
		'user_hair_color',
		'user_hair_type',
		'user_eyes',
		'user_make_date',
		'user_make_date_t',
		'user_refresh_date',
		'user_refresh_date_t',
		'user_session_time',
		'user_lastvisit',
		'user_ip',
		'user_submit_code',
		'user_description',
		'user_partner_description',
		'user_confirm_email',
		'user_active',
		'user_odobreno'
	];

	/**
	* The attributes that should be hidden for serialization.
	*
	* @var array<int, string>
	*/
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	* The attributes that should be cast.
	*
	* @var array<string, string>
	*/
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	protected $table 		= 'users_news';
	protected $primaryKey 	= 'user_id';
	public $timestamps 		= false;

	public $fieldsAboutPartner = 
	[
		'user_partner_sex',
		'partner_age',
		'partner_height',
		'partner_weight',
		'partner_country',
		'partner_region',
		'partner_city',
		'partner_speak_lang',
		'partner_education',
		'partner_smoke',
		'partner_spirt'
	];

	const SEX_BISEXUAL	= 1;
	const SEX_HETERO	= 2;
	const SEX_HOMO		= 3;
	const SEX_TRANS		= 4;

	/**
	* Get user property from the model
	* @param array $item
	* @param string $k
	* @return void
	*/
	public function getProperty($item, $k)
	{
		if ((int)$this->{$item['prop']} > 0)
		{
			$oItem 		= $k::getById ($this->{$item['prop']});
			$this->{$item['ank_prop']} 	= $oItem->name;
		}
	}

	/**
	* Get user multiple property from the model
	* @param array $item
	* @param string $k
	* @return void
	*/
	public function getPropertyFew($class, $prop,$propOut)
	{
		$unserProp = unserialize($prop);
		if ($prop != "N;" && !empty($unserProp[0]))
		{
			$obj = $class::getAll();

			$i = 0;
			$this->$propOut = '';
			$ar = [];
			foreach ($unserProp as $k=>$v)
			{
				$i++;
				foreach ($obj as $mT)
				{
					if ($v == $mT->id)
					{
						$ar[] = $mT->name;
						break;
					}
				}
			}
			$this->$propOut = implode (', ', $ar);
		}
	}

	public function getUserAgeAttribute ()
	{
		return Helper::age($this->user_birth_date);
	}

	public function getUserAgeTypeAttribute ()
	{
		return Helper::ageType($this->user_age);
	}

	public function getBirthDayAttribute ()
	{
		return Helper::selectFromDate($this->user_birth_date, DATE_DAY);
	}

	public function getBirthMonthAttribute ()
	{
		return Helper::selectFromDate($this->user_birth_date, DATE_MONTH);
	}

	public function getBirthYearAttribute ()
	{
		return Helper::selectFromDate($this->user_birth_date, DATE_YEAR);
	}

	public function getUserAgeStrAttribute ()
	{
		return $this->user_age .' ' . $this->user_age_type;
	}

	public function getFindSexOrientAttribute ()
	{
		$findSOrient = '';
		if ($this->user_sex_orient == GOMOSEXUAL) 
			$findSOrient .= $this->user_sex == MEN ? 'парня' : 'девушку';
		elseif ($this->user_sex_orient == BISEXUAL) 
			$findSOrient .= $this->user_sex == MEN ? 'девушку или парня' : 'парня или девушку';
		else
			$findSOrient .= $this->user_sex == MEN ? 'девушку' : 'парня';
	
		if ($this->user_partner_age_min > PARTNER_AGE_MIN && $this->user_partner_age_max > PARTNER_AGE_MAX) 
		{
			$findSOrient .= ' ' . $this->user_partner_age_min . '-' . $this->user_partner_age_max;
			$findSOrient .= ' ' . Helper::ageType($this->user_partner_age_max);
		} elseif ($this->user_partner_age_min > PARTNER_AGE_MIN && $this->user_partner_age_max <= PARTNER_AGE_MAX) 
		{
			$findSOrient .= ' от ' . $this->user_partner_age_min;
			$findSOrient .= ' ' . Helper::ageType2($this->user_partner_age_min);
		} elseif ($this->user_partner_age_min <= PARTNER_AGE_MIN && $this->user_partner_age_max > PARTNER_AGE_MAX)
		{
			$findSOrient .= ' до ' . $this->user_partner_age_max;
			$findSOrient .= ' ' . Helper::ageType2($this->user_partner_age_max);
		}
		return $findSOrient;
	}

	public function getZodiacAttribute ()
	{
		return Helper::zodiac($this->user_birth_date);
	}

	public function getNumberDiaryAttribute ()
	{
		return count($this->diary);
	}

	public function getNumberDiaryStrAttribute ()
	{
		return $this->number_diary . ' ' . Helper::caseDiaryType ($this->number_diary);
	}

	public function getUserReitingStrAttribute ()
	{
		$maxReit = (new UserRepository())->getMaxReiting($this->user_sex);
		return Helper::reiting ($this->user_reiting,$maxReit);
	}

	public function getUserDescriptionAttribute ($val)
	{
		$val = stripslashes($val);
		return str_replace("\n", "\n<br />\n", $val);
	}

	public function getUserSexStrAttribute ()
	{
		return $this->user_sex == MEN ? 'Мужской' : 'Женский';
	}

	public function getDateMakeStrAttribute ()
	{
		return Helper::dateFormat($this->user_make_date);
	}


	public function getDateRefreshAttribute ($val)
	{
		return $this->user_make_date !== $this->user_refresh_date ? Helper::dateFormat($this->user_refresh_date) : $val;
	}

	public function getMeetTargetAttribute ()
	{
		return unserialize($this->user_target_meet);
	}

	public function getSpeakLangAttribute ()
	{
		return unserialize($this->user_speak_lang);
	}

	public function getInterestsAttribute ()
	{
		return unserialize($this->user_interests);
	}

	public function getUserICQAttribute ($val)
	{
		return (int)$val > 0 ? $val : '';
	}

	public function getUserPartnerDescriptionAttribute ($val)
	{
		$val = stripslashes(trim($val));
		return str_replace("\n", "\n<br />\n", $val);
	}

	public function getPhotoIdAttribute ($val)
	{
		if (empty ($this->photo)) return null;
		return !empty($this->photo->fotos_id) ? $this->photo->fotos_id : null;
	}

	public function getUserClassAAttribute ()
	{
		return $this->user_sex == MEN ? 'name_man' : 'name_woman';
	}

	public function getNameClassAttribute ()
	{
		return  $this->user_sex == MEN ? 'name_man' : 'name_woman';
	}

	public function getUserPartnerSexAttribute ()
	{
		if ($this->user_sex_orient == self::SEX_BISEXUAL || $this->user_sex_orient == self::SEX_TRANS) 
		{
			$partnerSex = 'Мужской, Женский';
		} elseif ($this->user_sex_orient == self::SEX_HETERO) 
		{
			$partnerSex = $this->user_sex == MEN ? 'Женский' : 'Мужской';
		} else 
		{
			$partnerSex = $this->user_sex == WOMEN ? 'Женский' : 'Мужской';
		}
		return  $partnerSex;
	}

	public function getPartnerAgeAttribute ()
	{
		if (!($this->user_partner_age_min > PARTNER_AGE_MIN || $this->user_partner_age_max > PARTNER_AGE_MAX)) return null;
		if ($this->user_partner_age_min > PARTNER_AGE_MIN && $this->user_partner_age_max > PARTNER_AGE_MAX) 
			return ' ' . $this->user_partner_age_min . '-' . $this->user_partner_age_max . ' ' . Helper::ageType($this->user_partner_age_max);
		if ($this->user_partner_age_min > PARTNER_AGE_MIN && $this->user_partner_age_max <= PARTNER_AGE_MAX) 
			return ' от ' . $this->user_partner_age_min . ' ' . Helper::ageType2($this->user_partner_age_min);
		return ' до ' . $this->user_partner_age_max . ' ' . Helper::ageType2($this->user_partner_age_max);
	}

	public function getPartnerHeightAttribute ()
	{
		if (!($this->user_partner_height_min > PARTNER_HEIGHT_MIN || $this->user_partner_height_max > PARTNER_HEIGHT_MAX)) return null;
		if ($this->user_partner_height_min > PARTNER_HEIGHT_MIN && $this->user_partner_height_max > PARTNER_HEIGHT_MAX) 
			return ' ' . $this->user_partner_height_min . '-' . $this->user_partner_height_max . ' см';
		if ($this->user_partner_height_min > PARTNER_HEIGHT_MIN && $this->user_partner_height_max <= PARTNER_HEIGHT_MAX) 
			return ' от ' . $this->user_partner_height_min . ' см';
		return	' до ' . $this->user_partner_height_max . 'см';
	}

	public function getPartnerWeightAttribute ()
	{
		if (!($this->user_partner_weight_min > PARTNER_WEIGHT_MIN || $this->user_partner_weight_max > PARTNER_WEIGHT_MAX))  return null;
		if ($this->user_partner_weight_min > PARTNER_WEIGHT_MIN && $this->user_partner_weight_max > PARTNER_WEIGHT_MAX) 
			return ' ' . $this->user_partner_weight_min . '-' . $this->user_partner_weight_max . ' кг'; 
		if ($this->user_partner_weight_min > PARTNER_WEIGHT_MIN && $this->user_partner_weight_max <= PARTNER_WEIGHT_MAX) 
			return ' от ' . $this->user_partner_weight_min . ' кг';
		return ' до ' . $this->user_partner_weight_max . 'кг';
	}

	public function country()
	{
		return $this->hasOne(Country::class, 'id', 'user_country');
	}

	public function region()
	{
		return $this->hasOne(Region::class, 'id', 'user_region');
	}

	public function city()
	{
		return $this->hasOne(City::class, 'id', 'user_city');
	}

	public function photo()
	{
		return $this->hasMany(Photo::class, 'user_id', 'user_id')->with('comment')->orderBy('fotos_portret', 'desc');
	}

	public function visits()
	{
		$t = time() - 60*60*24*30;
		return $this->hasMany(AnketVisit::class, 'user_id_prosm', 'user_id')->where('ank_time', '>', $t);
	}

	public function diary()
	{
		return $this->hasMany(Diary::class, 'dnevniki_user_id', 'user_id')->orderBy('dnevniki_time', 'desc');
	}

	public function anketVisit()
	{
		return $this->hasOne(AnketVisit::class, 'user_id_prosm', 'user_id');
	}
}