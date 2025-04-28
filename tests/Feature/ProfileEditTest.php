<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\hasSetupPrepare;
use App\Repositories\UserRepository;
use App\Repositories\CountryRepository;
use App\Repositories\RegionRepository;
use App\Repositories\CityRepository;
use App\Http\Controllers\RegistrationController;
use App\Services\DataService;
use App\Services\FormatService;
use App\Models\User;

class ProfileEditTest extends TestCase
{
	use DatabaseMigrations, hasSetupPrepare;

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
		$this->user = $user;
	}

	/** @test */
	public function check_register_user(): void
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

	/** @test */
	public function check_profile_edit_main_page(): void
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
		$regions	= $countryId > 0 	? $this->regionRepository->getByCountryId($countryId) 	: [];
		$cities		= $regionId	> 0 	? $this->cityRepository->getByRegionId($regionId) 		: [];

		$response->assertViewHasAll([
			'days'			=> $days,
			'months'		=> $months,
			'years'			=> $years,
			'countries'		=> $countries,
			'regions'		=> $regions,
			'cities'		=> $cities
		]);
	}

	/** @test */
	public function check_profile_edit_second_page(): void
	{
		$url = $_SERVER['REQUEST_URI'] = route('registration.edit.second');
		$response = $this->get($url);
		$response->assertRedirectToRoute('login');

		$response = $this->actingAs($this->user)->get($url);
		$response->assertStatus(200);

		$sexOrient		= $this->formService->BlockSelect('sex_orient', SEX_ORIENT_CLASS, $this->user->sex_orient, 2);
		$targets		= $this->formService->BlockSelect('targets', MEET_TARGET_CLASS, $this->user->targets, 2);
		$userSpeakLang	= $this->formService->preparePropfromArray($this->user->speak_lang, RegistrationController::$languageCodes);
		$body 			= $this->formService->BlockSelect("body", BODY_CLASS, $this->user->body, 2);
		$heights 		= $this->formService->getHeights();
		$weights 		= $this->formService->getWeights();
		$hairColor 		= $this->formService->BlockSelect("hair_color", HAIR_COLOR_CLASS, $this->user->hair_color, 2);
		$hairType 		= $this->formService->BlockSelect("hair_type", HAIR_TYPE_CLASS, $this->user->hair_type, 2);
		$eyes	 		= $this->formService->BlockSelect("eyes", EYES_CLASS, $this->user->eyes, 2);
		$education 		= $this->formService->BlockSelect("education", EDUCATION_CLASS, $this->user->education, 2);
		$smoke 			= $this->formService->BlockSelect("smoke", SMOKE_CLASS, $this->user->smoke, 2);
		$alcohol		= $this->formService->BlockSelect("alcohol", SPIRT_CLASS, $this->user->alcohol, 2);
		$familyStatus	= $this->formService->BlockSelect("family_status", FAMILY_STATUS_CLASS, $this->user->family_status, 2);
		$children		= $this->formService->BlockSelect("children", CHILDREN_CLASS, $this->user->children, 2);
		$helpMoney		= $this->formService->BlockSelect("help_money", HELP_MONEY_CLASS, $this->user->help_money, 2);
		$interests		= $this->formService->BlockSelect("interests", INTEREST_CLASS, $this->user->interests, 2);

		$response->assertViewHasAll(
			[
				'userData'		=> $this->user,
				'sexOrient'		=> $sexOrient,
				'targets'		=> $targets,
				'userSpeakLang'	=> $userSpeakLang,
				'body'			=> $body,
				'heights'		=> $heights,
				'weights'		=> $weights,
				'hairColor'		=> $hairColor,
				'hairType'		=> $hairType,
				'eyes'			=> $eyes,
				'education'		=> $education,
				'smoke'			=> $smoke,
				'alcohol'		=> $alcohol,
				'familyStatus'	=> $familyStatus,
				'children'		=> $children,
				'helpMoney'		=> $helpMoney,
				'interests'		=> $interests
			]
		);
	}

	/** @test */
	public function check_profile_edit_partner_page(): void
	{
		$url = $_SERVER['REQUEST_URI'] = route('registration.edit.partner');
		$response = $this->get($url);
		$response->assertRedirectToRoute('login');

		$response = $this->actingAs($this->user)->get($url);
		$response->assertStatus(200);

		$age				= $this->dataService->getAges();
		$heights			= $this->formService->getHeights();
		$weights			= $this->formService->getWeights();
		$partnerBody		= $this->formService->BlockSelect("partner_body[]", BODY_CLASS, old('partner_body', $this->user->partner_body), 2);
		$partnerLanguages	= $this->formService->BlockSelect("partner_languages[]", SPEAK_LANG_CLASS, old('partner_languages', $this->user->partner_languages), 2);
		$partnerAlcohol		= $this->formService->BlockSelect("partner_alcohol[]", SPIRT_CLASS, old('partner_alcohol', $this->user->partner_alcohol), 2);
		$partnerSmoke		= $this->formService->BlockSelect("partner_smoke[]", SMOKE_CLASS, old('partner_smoke', $this->user->partner_smoke), 2);
		$partnerEducation	= $this->formService->BlockSelect("partner_education[]", EDUCATION_CLASS, old('partner_education', $this->user->partner_education), 2);

		$countries	= $this->countryRepository->getAll();
		$countryId	= (int) old('country', $this->user->partner_country);
		$regionId	= (int) old('region', $this->user->partner_region);
		$regions	= $countryId > 0	? $this->regionRepository->getByCountryId($countryId) 	: [];
		$cities		= $regionId	> 0		? $this->cityRepository->getByRegionId($regionId) 		: [];

		$response->assertViewHasAll(
			[
				'userData'			=> $this->user,
				'age'				=> $age,
				'heights'			=> $heights,
				'weights'			=> $weights,
				'partnerBody'		=> $partnerBody,
				'partnerLanguages'	=> $partnerLanguages,
				'partnerAlcohol'	=> $partnerAlcohol,
				'partnerSmoke'		=> $partnerSmoke,
				'partnerEducation'	=> $partnerEducation,
				'countries'			=> $countries,
				'regions'			=> $regions,
				'cities'			=> $cities,
			]
		);
	}
}
