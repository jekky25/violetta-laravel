<?

namespace App\Services;

class AnkService
{
	protected $anket;
	public static $getProps = [
		'\\App\\Repositories\\BodyRepository'			=> ['prop' => 'body', 					'ank_prop' => 'body'],
		'\\App\\Repositories\\HairColorRepository'		=> ['prop' => 'hair_color',		 		'ank_prop' => 'hair_color'],
		'\\App\\Repositories\\HairTypeRepository' 		=> ['prop' => 'hair_type',		 		'ank_prop' => 'hair_type'],
		'\\App\\Repositories\\EyesRepository' 			=> ['prop' => 'eyes',		 			'ank_prop' => 'eyes'],
		'\\App\\Repositories\\FamilyStatusRepository' 	=> ['prop' => 'family_status',			'ank_prop' => 'family_status'],
		'\\App\\Repositories\\ChildrenRepository' 		=> ['prop' => 'children',				'ank_prop' => 'children'],
		'\\App\\Repositories\\EducationRepository' 		=> ['prop' => 'education',				'ank_prop' => 'education'],
		'\\App\\Repositories\\SmokeRepository' 			=> ['prop' => 'smoke',					'ank_prop' => 'smoke'],
		'\\App\\Repositories\\AlcoholRepository' 		=> ['prop' => 'alcohol',				'ank_prop' => 'alcohol'],
		'\\App\\Repositories\\HelpMoneyRepository' 		=> ['prop' => 'help_money',				'ank_prop' => 'help_money'],
		'\\App\\Repositories\\SexOrientRepository' 		=> ['prop' => 'sex_orient',				'ank_prop' => 'sex_orient'],
		'\\App\\Repositories\\CountryRepository'		=> ['prop' => 'partner_country',		'ank_prop' => 'partner_country'],
		'\\App\\Repositories\\RegionRepository'			=> ['prop' => 'partner_region',			'ank_prop' => 'partner_region'],
		'\\App\\Repositories\\CityRepository'			=> ['prop' => 'partner_city',			'ank_prop' => 'partner_city']
	];

	public function __construct(&$anket)
	{
		$this->anket = $anket;
	}

	/**
	 * get user parameters on the anket page
	 * @return void
	 */
	public function prepare()
	{
		$this->getTargets();
		$this->getInterests();
	}

	/**
	 * get addition user parameters on the full anket page
	 * @return void
	 */
	public function prepareFull()
	{
		$this->getAddProps();
		$this->getSpeakLang();
		$this->getPartnerBody();
		$this->getPartnerSpeakLang();
		$this->getPartnerEducation();
		$this->getPartnerSmoke();
		$this->getPartnerAlcohol();
	}

	private function getAddProps()
	{
		foreach (self::$getProps as $k => $item) {
			$this->anket->getProperty($item, $k);
		}
	}

	private function getInterests()
	{
		$this->anket->getPropertyFew('App\Repositories\InterestRepository',		$this->anket->interests,			'interests_out');
	}

	private function getTargets()
	{
		$this->anket->getPropertyFew('App\Repositories\MeetTargetRepository',	$this->anket->targets,			'targets_out');
	}

	private function getSpeakLang()
	{
		$this->anket->getPropertyFew('App\Repositories\SpeakLangRepository',	$this->anket->speak_language,		'speak_lang_str');
	}

	private function getPartnerBody()
	{
		$this->anket->getPropertyFew('\App\Repositories\BodyRepository',		$this->anket->partner_body,			'partner_body');
	}

	private function getPartnerSpeakLang()
	{
		$this->anket->getPropertyFew('App\Repositories\SpeakLangRepository',	$this->anket->partner_languages,	'partner_languages');
	}

	private function getPartnerEducation()
	{
		$this->anket->getPropertyFew('App\Repositories\EducationRepository',	$this->anket->partner_education,	'partner_education');
	}

	private function getPartnerSmoke()
	{
		$this->anket->getPropertyFew('App\Repositories\SmokeRepository',		$this->anket->partner_smoke,		'partner_smoke');
	}

	private function getPartnerAlcohol()
	{
		$this->anket->getPropertyFew('App\Repositories\AlcoholRepository',		$this->anket->partner_alcohol,		'partner_alcohol');
	}

	/**
	 * check existing parameters about partner
	 * @return bool
	 */
	public function isAboutPartner()
	{
		foreach ($this->anket->fieldsAboutPartner as $prop) {
			if (!empty($this->anket->$prop)) return true;
		}
		return false;
	}

	/**
	 * make found string
	 * @return string
	 */
	public function getFoundStr()
	{
		if (empty($this->anket)) return 'Найдено анкет: 0';
		$count = config('pagination.profiles');
		$start 		= (($this->anket->currentPage() - 1) * $count) + 1;
		$end		= $this->anket->currentPage() * $count;
		$end		= $end < $this->anket->total() ? $end : $this->anket->total();
		return 'Найдено анкет: (' . $start . '-' . $end . ') из ' . $this->anket->total();
	}
}
