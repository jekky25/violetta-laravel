<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Repositories\UserRepository;
use App\Services\FormatService;
use App\Services\DataService;
use App\Models\Photo;
use App\Traits\HasFilter;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, HasFilter;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'email',
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
		'make_date',
		'make_date_t',
		'refresh_date',
		'refresh_date_t',
		'session_time',
		'lastvisit',
		'lastvisit_views',
		'ip',
		'submit_code',
		'description',
		'partner_description',
		'confirm_email',
		'user_active',
		'approved',
		'sex_orient',
		'targets',
		'speak_lang',
		'education',
		'smoke',
		'alcohol',
		'user_sem_polozh',
		'user_children',
		'help_money',
		'interests',
		'icq',
		'url',
		'phone',
		'partner_age_min',
		'partner_age_max',
		'partner_height_min',
		'partner_height_max',
		'partner_weight_min',
		'partner_weight_max',
		'partner_body',
		'partner_languages',
		'partner_alcohol',
		'partner_smoke',
		'partner_education',
		'partner_country',
		'partner_region',
		'partner_city',
		'session_time',
		'dont_send_email'
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
		'partner_languages',
		'partner_education',
		'partner_smoke',
		'partner_alcohol'
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
		if ((int)$this->{$item['prop']} > 0) {
			$oItem 		= $k::getById($this->{$item['prop']});
			$this->{$item['ank_prop']} 	= $oItem->name;
		}
	}

	/**
	 * Get user multiple property from the model
	 * @param array $item
	 * @param string $k
	 * @return void
	 */
	public function getPropertyFew($class, $prop, $propOut)
	{
		$unserProp = unserialize($prop);
		if ($prop != "N;" && !empty($unserProp[0])) {
			$obj = $class::getAll();

			$i = 0;
			$this->$propOut = '';
			$ar = [];
			foreach ($unserProp as $k => $v) {
				$i++;
				foreach ($obj as $mT) {
					if ($v == $mT->id) {
						$ar[] = $mT->name;
						break;
					}
				}
			}
			$this->$propOut = implode(', ', $ar);
		}
	}

	public function getUserAgeAttribute()
	{
		return (new DataService)->age($this->user_birth_date);
	}

	public function getUserAgeTypeAttribute()
	{
		return (new formatService)->ageType($this->user_age);
	}

	public function getBirthDayAttribute()
	{
		return (new DataService)->selectFromDate($this->user_birth_date, DATE_DAY);
	}

	public function getBirthMonthAttribute()
	{
		return (new DataService)->selectFromDate($this->user_birth_date, DATE_MONTH);
	}

	public function getBirthYearAttribute()
	{
		return (new DataService)->selectFromDate($this->user_birth_date, DATE_YEAR);
	}

	public function getUserAgeStrAttribute()
	{
		return $this->user_age . ' ' . $this->user_age_type;
	}

	public function getFindSexOrientAttribute()
	{
		$findSOrient = '';
		if ($this->sex_orient == GOMOSEXUAL)
			$findSOrient .= $this->user_sex == MEN ? 'парня' : 'девушку';
		elseif ($this->sex_orient == BISEXUAL)
			$findSOrient .= $this->user_sex == MEN ? 'девушку или парня' : 'парня или девушку';
		else
			$findSOrient .= $this->user_sex == MEN ? 'девушку' : 'парня';

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
		return (new DataService)->zodiac($this->user_birth_date);
	}

	public function getNumberDiaryAttribute()
	{
		return count($this->diary);
	}

	public function getNumberDiaryStrAttribute()
	{
		return $this->number_diary . ' ' . (new formatService)->caseDiaryType($this->number_diary);
	}

	public function getUserReitingStrAttribute()
	{
		$maxReit = (new UserRepository())->getMaxReiting($this->user_sex);
		return (new formatService)->reiting($this->user_reiting, $maxReit);
	}

	public function getDescriptionAttribute($val)
	{
		$val = stripslashes($val);
		return str_replace("\n", "\n<br />\n", $val);
	}

	public function getUserSexStrAttribute()
	{
		return $this->user_sex == MEN ? 'Мужской' : 'Женский';
	}

	public function getDateMakeStrAttribute()
	{
		return (new DataService)->dateFormat($this->make_date);
	}


	public function getDateRefreshAttribute($val)
	{
		return $this->make_date !== $this->refresh_date ? (new DataService)->dateFormat($this->refresh_date) : $val;
	}

	public function getSpeakLangAttribute($val)
	{
		return unserialize($val);
	}

	public function getICQAttribute($val)
	{
		return (int)$val > 0 ? $val : '';
	}

	public function getPartnerDescriptionAttribute($val)
	{
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

	public function getUserClassAAttribute()
	{
		return $this->user_sex == MEN ? 'name_man' : 'name_woman';
	}

	public function getNameClassAttribute()
	{
		return  $this->user_sex == MEN ? 'name_man' : 'name_woman';
	}

	public function getOnTopAttribute()
	{
		return '<strong>' . ($this->user_sex == WOMEN ? 'поднялась' : 'поднялся') . '</strong>: ' . (new DataService)->lastVisit($this->top100);
	}

	public function getUserPartnerSexAttribute()
	{
		if ($this->sex_orient == self::SEX_BISEXUAL || $this->sex_orient == self::SEX_TRANS) {
			$partnerSex = 'Мужской, Женский';
		} elseif ($this->sex_orient == self::SEX_HETERO) {
			$partnerSex = $this->user_sex == MEN ? 'Женский' : 'Мужской';
		} else {
			$partnerSex = $this->user_sex == WOMEN ? 'Женский' : 'Мужской';
		}
		return  $partnerSex;
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
		return $this->hasMany(Photo::class, 'user_id', 'user_id')->with('comment')->orderBy('main_picture', 'desc');
	}

	public function visits()
	{
		$t = time() - 60 * 60 * 24 * 30;
		return $this->hasMany(AnketVisit::class, 'user_id_prosm', 'user_id')->where('create_time', '>', $t);
	}

	public function diary()
	{
		return $this->hasMany(Diary::class, 'user_id', 'user_id')->orderBy('create_time', 'desc');
	}

	public function anketVisit()
	{
		return $this->hasOne(AnketVisit::class, 'user_id_prosm', 'user_id');
	}
}
