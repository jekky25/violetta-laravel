<?

namespace App\Services;

use App\Models\User;

class AnkService
{
	protected User $anket;
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
		'\\App\\Repositories\\CountryRepository'		=> ['prop' => 'partner_country',		'ank_prop' => 'partner_country'],
		'\\App\\Repositories\\RegionRepository'			=> ['prop' => 'partner_region',			'ank_prop' => 'partner_region'],
		'\\App\\Repositories\\CityRepository'			=> ['prop' => 'partner_city',			'ank_prop' => 'partner_city']
	];

	/**
	 * get user parameters on the anket page

	 */
	public function prepare(User $anket, string $mode): User
	{
		$this->anket = $anket;
		if ($mode == 'full') $this->prepareFull();

		return $this->anket;
	}

	/**
	 * get addition user parameters on the full anket page
	 * @return void
	 */
	private function prepareFull()
	{
		$this->getAddProps();
	}

	private function getAddProps()
	{
		foreach (self::$getProps as $k => $item) {
			$this->anket->getProperty($item, $k);
		}
	}
}
