<?
namespace App\Services;

class AnkService
{
	protected $anket;
	public static $getProps = [
		'\\App\\Repositories\\BodyRepository'			=> ['prop' =>'user_body', 				'ank_prop' =>'body'],
		'\\App\\Repositories\\HairColorRepository'		=> ['prop' =>'user_hair_color', 		'ank_prop' =>'hair_color'],
		'\\App\\Repositories\\HairTypeRepository' 		=> ['prop' =>'user_hair_type', 			'ank_prop' =>'hair_type'],
		'\\App\\Repositories\\EyesRepository' 			=> ['prop' =>'user_eyes',	 			'ank_prop' =>'eyes'],
		'\\App\\Repositories\\FamilyStatusRepository' 	=> ['prop' =>'user_sem_polozh',			'ank_prop' =>'family_status'],
		'\\App\\Repositories\\ChildrenRepository' 		=> ['prop' =>'user_children',			'ank_prop' =>'children'],
		'\\App\\Repositories\\EducationRepository' 		=> ['prop' =>'user_education',			'ank_prop' =>'education'],
		'\\App\\Repositories\\SmokeRepository' 			=> ['prop' =>'user_smoke',				'ank_prop' =>'smoke'],
		'\\App\\Repositories\\SpirtRepository' 			=> ['prop' =>'user_spirt',				'ank_prop' =>'spirt'],
		'\\App\\Repositories\\HelpMoneyRepository' 		=> ['prop' =>'user_help_money',			'ank_prop' =>'help_money'],
		'\\App\\Repositories\\SexOrientRepository' 		=> ['prop' =>'user_sex_oriebt',			'ank_prop' =>'sex_orient'],
		'\\App\\Repositories\\CountryRepository'		=> ['prop' =>'user_partner_country',	'ank_prop' =>'partner_country'],
		'\\App\\Repositories\\RegionRepository'			=> ['prop' =>'user_partner_region',		'ank_prop' =>'partner_region'],
		'\\App\\Repositories\\CityRepository'			=> ['prop' =>'user_partner_city',		'ank_prop' =>'partner_city']
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
		$this->getTargetMeet();
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
		$this->getPartnerSpirt();
	}

	private function getAddProps()
	{
		foreach (self::$getProps as $k => $item)
		{
			$this->anket->getProperty($item, $k);
		}
	}

	private function getInterests()
	{
		$this->anket->getPropertyFew('App\Repositories\InterestRepository',		$this->anket->user_interests,			'interests');
	}

	private function getTargetMeet()
	{
		$this->anket->getPropertyFew('App\Repositories\MeetTargetRepository',	$this->anket->user_target_meet,			'target_meet');
	}

	private function getSpeakLang()
	{
		$this->anket->getPropertyFew('App\Repositories\MeetTargetRepository',	$this->anket->user_speak_lang,			'speak_lang_str');
	}

	private function getPartnerBody()
	{
		$this->anket->getPropertyFew('\App\Repositories\BodyRepository',		$this->anket->user_partner_body,		'partner_body');
	}

	private function getPartnerSpeakLang()
	{
		$this->anket->getPropertyFew('App\Repositories\SpeakLangRepository',	$this->anket->user_partner_speak_lang,	'partner_speak_lang');
	}

	private function getPartnerEducation()
	{
		$this->anket->getPropertyFew('App\Repositories\EducationRepository',	$this->anket->user_partner_education,	'partner_education');
	}

	private function getPartnerSmoke()
	{
		$this->anket->getPropertyFew('App\Repositories\SmokeRepository',		$this->anket->user_partner_smoke,		'partner_smoke');
	}

	private function getPartnerSpirt()
	{
		$this->anket->getPropertyFew('App\Repositories\SpirtRepository',		$this->anket->user_partner_spirt,		'partner_spirt');
	}

	/**
	* check existing parameters about partner
	* @return bool
	*/
	public function isAboutPartner()
	{
		foreach ($this->anket->fieldsAboutPartner as $prop)
		{
			if (!empty ($this->anket->$prop)) return true;
		}
		return false;
	}

	/**
	* make found string
	* @param integer $count
	* @return string
	*/
	public function getFoundStr($count = 0)
	{
		if (empty($this->anket)) return 'Найдено анкет: 0';
		$start 		= (($this->anket->currentPage() - 1) * $count) + 1;
		$end		= $this->anket->currentPage() * $count;
		$end		= $end < $this->anket->total() ? $end : $this->anket->total();
		return 'Найдено анкет: (' . $start . '-' . $end . ') из ' . $this->anket->total();
	}
}