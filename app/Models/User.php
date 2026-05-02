<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Repositories\AnketVisitRepository;
use App\Services\DataService;
use App\Models\Photo;
use App\Traits\HasFilter;
use App\Traits\HasUserModelAttributes;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, HasFilter, HasUserModelAttributes;

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
		'remember_token',
		'top100'
	];

	protected $appends = [
		'rating_str'
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
	 */
	public function getPropertyFew(string|object $class, mixed $prop): string
	{
		$unserProp = is_string($prop) ? unserialize($prop) : $prop;

 		if (is_integer($unserProp)) return $class::getById($unserProp)?->name;

		if ($prop != "N;" && is_array($unserProp)) {
			$obj = $class::getAll();
			$i = 0;
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
			return implode(', ', $ar);
		}
		return '';
	}

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeEmail($query, $email)
	{
		return $query->where('email', $email);
	}

	public function scopeConfirmed($query)
	{
		return $query->where('confirm_email', 1);
	}

	public function scopeLogin($query, $login)
	{
		return $query->where('login', $login);
	}

	public function scopeActive($query)
	{
		return $query->where('active', 1);
	}

	public function scopeSex($query, $sex)
	{
		return $query->where('sex', $sex);
	}

	public function scopeHash($query, $pass)
	{
		return $query->where('hash', md5($pass));
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
