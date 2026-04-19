<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Repositories\UserRepository;
use App\Repositories\CountryRepository;
use App\Repositories\RegionRepository;
use App\Repositories\CityRepository;
use App\Services\DataService;
use App\Services\FormatService;
use App\Models\User;
use App\Models\Photo;
use App\Fields\ProfileEditField;
use App\Fields\ProfileSecondField;
use App\Fields\ProfilePartnerField;
use Illuminate\Database\Eloquent\Collection;

class ProfileEditTest extends TestCase
{
	use hasSetupPrepare;

	protected $data = [
		'login'					=> 'test25',
		'password'				=> '123456',
		'password_second'		=> '123456',
		'name'					=> 'test',
		'email'					=> 'test@test.ru',
		'sex'					=> MEN,
		'birth_date'			=> '1983-05-20',
		'country_id'			=> 141,
		'region_id'				=> 526,
		'city_id'				=> 1458,
		'conditions'			=> 1,
		'description'			=> '',
		'partner_description'	=> '',
		'ip'					=> '127.0.0.1',
		'remember_token'		=> 'assdddaddasd',
		'speak_lang'			=> '',
		'partner_body'			=> '',
		'partner_languages'		=> '',
		'partner_education'		=> '',
		'partner_smoke'			=> '',
		'partner_alcohol'		=> '',
		'targets'				=> '',
		'interests'				=> '',
		'phone'					=> '',
		'url'					=> '',
	];

	protected $userRepository;
	protected $countryRepository;
	protected $regionRepository;
	protected $cityRepository;
	protected $dataService;
	protected $formService;

	/**
	 * Set up variables
	 */
	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
		$this->userRepository		= new UserRepository;
		$this->dataService			= new DataService;
		$this->formService			= new FormatService;
		$this->countryRepository	= new CountryRepository;
		$this->regionRepository		= new RegionRepository;
		$this->cityRepository		= new CityRepository;

		$user = User::factory()->create();
		$data = [
				'user_id'					=> $user->id,
				'main_picture'				=> 1,
				'create_time'				=> time()
			];
		Photo::factory($data)->create();
		$this->user = $user;
	}

	public function test_check_register_user(): void
	{
		$this->userRepository->create($this->data);
		$user	= $this->userRepository->getByLogin($this->data['login']);
		$this->assertEquals($user->login, 			$this->data['login']);
		$this->assertEquals($user->password,	 	$this->data['password']);
		$this->assertEquals($user->name, 			$this->data['name']);
		$this->assertEquals($user->email, 			$this->data['email']);
		$this->assertEquals($user->sex, 			$this->data['sex']);
		$this->assertEquals($user->birth_date,		$this->data['birth_date']);
		$this->assertEquals($user->country_id, 		$this->data['country_id']);
		$this->assertEquals($user->region_id, 		$this->data['region_id']);
		$this->assertEquals($user->city_id, 		$this->data['city_id']);
	}

	public function test_check_profile_edit_main_page(): void
	{
		$url = $_SERVER['REQUEST_URI'] = route('registration.edit');
		$response = $this->get($url);
		$response->assertRedirectToRoute('login');

		$response = $this->actingAs($this->user)->get($url);
		$response->assertStatus(200);

		$days		= $this->dataService->getDays();
		$months		= $this->dataService->getMonths();
		$years		= $this->dataService->getYears();
		$countries	= $this->countryRepository->getAll();
		$countryId	= (int) old('country_id', $this->user->country_id);
		$regionId	= (int) old('region_id', $this->user->region_id);
		$regions	= $countryId > 0 	? $this->regionRepository->getByCountryId($countryId) 	: new Collection();
		$cities		= $regionId	> 0 	? $this->cityRepository->getByRegionId($regionId) 		: new Collection();

		$fields				= new ProfileEditField( $this->countryRepository,
													$this->dataService, 
													$this->formService, 
													$this->regionRepository, 
													$this->cityRepository );

		$this->assertEquals($this->user->name, $fields->user()->name);
		$this->assertEquals($this->user->sex, $fields->user()->sex);
		$this->assertEquals($days, $fields->day());
		$this->assertEquals($months, $fields->month());
		$this->assertEquals($years, $fields->year());

		$this->assertEquals($this->user->birth_day, $fields->user()->birth_day);
		$this->assertEquals($this->user->birth_month, $fields->user()->birth_month);
		$this->assertEquals($this->user->birth_year, $fields->user()->birth_year);

		$this->assertEquals($countries, $fields->country());
		$this->assertEquals($regions, $fields->region($fields->user()->country_id));
		$this->assertEquals($cities, $fields->city($fields->user()->region_id));

		$this->assertEquals($countryId, $fields->user()->country_id);
		$this->assertEquals($regionId, $fields->user()->region_id);
		$this->assertEquals($this->user->city_id, $fields->user()->city_id);
		
		$response->assertViewHasAll([ 'fields' => $fields ]);
	}

	public function test_check_profile_edit_second_page(): void
	{
		$url = $_SERVER['REQUEST_URI'] = route('registration.edit.second');
		$response = $this->get($url);
		$response->assertRedirectToRoute('login');

		$response = $this->actingAs($this->user)->get($url);
		$response->assertStatus(200);

		$sexOrient		= $this->formService->BlockSelect(SEX_ORIENT_CLASS, $this->user->sex_orient);
		$targets		= $this->formService->BlockSelect(MEET_TARGET_CLASS, $this->user->targets);
		$body 			= $this->formService->BlockSelect(BODY_CLASS, $this->user->body);
		$heights 		= $this->formService->getHeights();
		$weights 		= $this->formService->getWeights();
		$hairColor 		= $this->formService->BlockSelect(HAIR_COLOR_CLASS, $this->user->hair_color);
		$hairType 		= $this->formService->BlockSelect(HAIR_TYPE_CLASS, $this->user->hair_type);
		$eyes	 		= $this->formService->BlockSelect(EYES_CLASS, $this->user->eyes);
		$education 		= $this->formService->BlockSelect(EDUCATION_CLASS, $this->user->education);
		$smoke 			= $this->formService->BlockSelect(SMOKE_CLASS, $this->user->smoke);
		$alcohol		= $this->formService->BlockSelect(SPIRT_CLASS, $this->user->alcohol);
		$familyStatus	= $this->formService->BlockSelect(FAMILY_STATUS_CLASS, $this->user->family_status);
		$children		= $this->formService->BlockSelect(CHILDREN_CLASS, $this->user->children);
		$helpMoney		= $this->formService->BlockSelect(HELP_MONEY_CLASS, $this->user->help_money);
		$interests		= $this->formService->BlockSelect(INTEREST_CLASS, $this->user->interests);

		$fields				= new ProfileSecondField( $this->countryRepository,
													$this->dataService, 
													$this->formService, 
													$this->regionRepository, 
													$this->cityRepository );

		$this->assertEquals($this->user->sex_orient, $fields->user()->sex_orient);
		$this->assertEquals($sexOrient, $fields->sexOrient($fields->user()->sex_orient));
		$this->assertEquals($this->user->targets, $fields->user()->targets);
		$this->assertEquals($targets, $fields->targets($fields->user()->targets));
		$this->assertEquals($this->user->speak_lang, $fields->user()->speak_lang);
		$this->assertEquals($this->user->speak_lang, $fields->user()->speak_lang);
		$this->assertEquals($this->user->body, $fields->user()->body);
		$this->assertEquals($body, $fields->body($fields->user()->body));
		$this->assertEquals($heights, $fields->height());
		$this->assertEquals($weights, $fields->weight());
		$this->assertEquals($hairColor, $fields->hairColor($fields->user()->hair_color));
		$this->assertEquals($hairType, $fields->hairType($fields->user()->hair_type));
		$this->assertEquals($eyes, $fields->eyes($fields->user()->eyes));
		$this->assertEquals($education, $fields->education($fields->user()->education));
		$this->assertEquals($smoke, $fields->smoke($fields->user()->smoke));
		$this->assertEquals($alcohol, $fields->alcohol($fields->user()->alcohol));
		$this->assertEquals($familyStatus, $fields->familyStatus($fields->user()->family_status));
		$this->assertEquals($children, $fields->children($fields->user()->children));
		$this->assertEquals($helpMoney, $fields->helpMoney($fields->user()->help_money));
		$this->assertEquals($interests, $fields->interests($fields->user()->interests));

		$response->assertViewHasAll([ 'fields' => $fields ]);
	}

	public function test_check_profile_edit_partner_page(): void
	{
		$url = $_SERVER['REQUEST_URI'] = route('registration.edit.partner');
		$response = $this->get($url);
		$response->assertRedirectToRoute('login');

		$response = $this->actingAs($this->user)->get($url);
		$response->assertStatus(200);

		$age				= $this->dataService->getAges();
		$heights			= $this->formService->getHeights();
		$weights			= $this->formService->getWeights();
		$partnerBody		= $this->formService->BlockSelect(BODY_CLASS, $this->user->partner_body);
		$partnerLanguages	= $this->formService->BlockSelect(SPEAK_LANG_CLASS, $this->user->partner_languages);
		$partnerAlcohol		= $this->formService->BlockSelect(SPIRT_CLASS, $this->user->partner_alcohol);
		$partnerSmoke		= $this->formService->BlockSelect(SMOKE_CLASS, $this->user->partner_smoke);
		$partnerEducation	= $this->formService->BlockSelect(EDUCATION_CLASS, $this->user->partner_education);

		$countries	= $this->countryRepository->getAll();
		$countryId	= (int) old('country', $this->user->partner_country);
		$regionId	= (int) old('region', $this->user->partner_region);
		$regions	= $countryId > 0	? $this->regionRepository->getByCountryId($countryId) 	: new Collection();
		$cities		= $regionId	> 0		? $this->cityRepository->getByRegionId($regionId) 		: new Collection();

		$fields				= new ProfilePartnerField( $this->countryRepository,
													$this->dataService, 
													$this->formService, 
													$this->regionRepository, 
													$this->cityRepository );

		$this->assertEquals($age, $fields->age());
		$this->assertEquals($heights, $fields->height());
		$this->assertEquals($weights, $fields->weight());
		$this->assertEquals($partnerBody, $fields->body($fields->user()->partner_body));
		$this->assertEquals($partnerLanguages, $fields->languages($fields->user()->partner_languages));
		$this->assertEquals($partnerAlcohol, $fields->alcohol($fields->user()->partner_alcohol));
		$this->assertEquals($partnerSmoke, $fields->smoke($fields->user()->partner_smoke));
		$this->assertEquals($partnerEducation, $fields->education($fields->user()->partner_education));
		$this->assertEquals($countries, $fields->country());
		$this->assertEquals($regions, $fields->region($fields->user()->partner_country));
		$this->assertEquals($cities, $fields->city($fields->user()->partner_region));
		$this->assertEquals($countryId, $fields->user()->partner_country);
		$this->assertEquals($regionId, $fields->user()->partner_region);
		$this->assertEquals($this->user->partner_city, $fields->user()->partner_city);

		$response->assertViewHasAll([ 'fields' => $fields ]);
	}

	public function test_check_profile_change_password_page(): void
	{
		$url = $_SERVER['REQUEST_URI'] = route('registration.edit.password');
		$response = $this->get($url);
		$response->assertRedirectToRoute('login');

		$response = $this->actingAs($this->user)->get($url);
		$response->assertStatus(200);	
	}

	public function test_check_profile_photo_page(): void
	{
		$url = $_SERVER['REQUEST_URI'] = route('registration.edit.photo');
		$response = $this->get($url);
		$response->assertRedirectToRoute('login');

		$response = $this->actingAs($this->user)->get($url);
		$response->assertStatus(200);

		$response->assertViewHasAll(
			[
				'photos' => $this->user->photo
			]
		);
	}
}
