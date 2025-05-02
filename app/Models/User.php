<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Repositories\UserRepository;
use App\Repositories\AnketVisitRepository;
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
		'login',
		'password',
		'hash',
		'sex',
		'photos_count',
		'rating',
		'name',
		'birth_date',
		'country_id',
		'region_id',
		'city_id',
		'height',
		'weight',
		'body',
		'hair_color',
		'hair_type',
		'eyes',
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
		'active',
		'approved',
		'sex_orient',
		'targets',
		'speak_lang',
		'education',
		'smoke',
		'alcohol',
		'family_status',
		'children',
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
		'dont_send_email',
		'remember_token'
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
	protected $primaryKey 	= 'id';
	public $timestamps 		= false;
	protected $data;

	private $anketVisitRepository;

	public $fieldsAboutPartner =
	[
		'partner_sex',
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

	const NOT_APPROVED	= 0;
	const AGE_MIN		= 15;
	const HEIGHT_MIN	= 149;
	const WEIGHT_MIN	= 29;

	public function __construct(array $attributes = [])
	{
		$this->anketVisitRepository = new AnketVisitRepository;
		parent::__construct($attributes);
		$this->data = $this->createData();
	}

	/**
	 * Create an instance of DataService::class because of $this->data doesn't work over __construct in the unitTests
	 * @return DataService
	 */
	public function createData()
	{
		if (!$this->data instanceof DataService) $this->data = new DataService;
		return $this->data;
	}

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			$model->refresh_date	= date("Y-m-d");
			$model->refresh_date_t	= time();
			$model->session_time	= time();
			$model->lastvisit		= time();
			$model->make_date		= date("Y-m-d");
			$model->make_date_t		= time();
			$model->active 			= 1;
			$model->approved		= 1;
			$model->hash			= md5($model->password);
			$model->ip				= request()->ip();
			$model->submit_code 	= md5(time() . $model->login . rand(0, 1000));
			$model->confirm_email	= isset($model->confirm_email) ?: 0;
		});
		self::updating(function ($model) {
			$model->refresh_date	= date("Y-m-d");
			$model->refresh_date_t	= time();
			$model->session_time	= time();
			$model->lastvisit		= time();
			$model->approved		= $model->getOriginal('partner_description') !== $model->partner_description ? self::NOT_APPROVED : $model->approved;
		});
	}

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
		return $this->data->age($this->birth_date);
	}

	public function getUserAgeTypeAttribute()
	{
		return (new formatService)->ageType($this->user_age);
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

	public function getUserAgeStrAttribute()
	{
		return $this->user_age . ' ' . $this->user_age_type;
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
		return count($this->anketVisitRepository->visitsNew($this));
	}

	public function getMonthVisitsAttribute()
	{
		return count($this->visits);
	}

	public function getLastvisitFormatAttribute()
	{
		return $this->data->getDate($this->lastvisit);
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

	public function setUrlAttribute($val)
	{
		$this->attributes['url'] = addslashes($val);
	}

	public function setPhoneAttribute($val)
	{
		$this->attributes['phone'] = addslashes($val);
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


	/***********************************
	 * RELATIONS
	 ***********************************/

	public function country(): HasOne
	{
		return $this->hasOne(Country::class, 'id', 'country_id');
	}

	public function region(): HasOne
	{
		return $this->hasOne(Region::class, 'id', 'region_id');
	}

	public function city(): HasOne
	{
		return $this->hasOne(City::class, 'id', 'city_id');
	}

	public function anketVisit(): HasOne
	{
		return $this->hasOne(AnketVisit::class, 'user_id_prosm', 'id');
	}

	public function photo(): HasMany
	{
		return $this->hasMany(Photo::class, 'user_id', 'id')->with('comment')->orderBy('main_picture', 'desc');
	}

	public function visits(): HasMany
	{
		$t = time() - 60 * 60 * 24 * 30;
		return $this->hasMany(AnketVisit::class, 'user_id_prosm', 'id')->where('create_time', '>', $t);
	}

	public function diary(): HasMany
	{
		return $this->hasMany(Diary::class, 'user_id', 'id')->orderBy('create_time', 'desc');
	}
}
