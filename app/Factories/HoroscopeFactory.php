<?

namespace App\Factories;

use App\includes\Horoscope\ZodiacHoroscope;
use App\includes\Horoscope\EasternHoroscope;
use App\includes\Horoscope\GallHoroscope;
use App\includes\Horoscope\FloversHoroscope;
use App\includes\Horoscope\TalismanHoroscope;

class HoroscopeFactory
{
	/**
	* make horoscope instances from the classes
	* @param int $type
	* @return ZodiacHoroscope|EasternHoroscope|GallHoroscope|FloversHoroscope|TalismanHoroscope
	*/
	public function make(int $type)
	{
		$map = [
			1 => ZodiacHoroscope::class,
			2 => EasternHoroscope::class,
			3 => GallHoroscope::class,
			4 => FloversHoroscope::class,
			5 => TalismanHoroscope::class,
		];

		return app($map[$type] ?? $map[1]);
	}
}
