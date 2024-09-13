<?
namespace App\Services;

class FileService
{
	/**
	 * formate string to mB or kB
	 * @param string $str
	 *
	 * @return string
	 */
	public static function formatFileSize($str)
	{
		$l = strlen($str);
		if ($l > 6)
		{
			$l1		= substr($str,0,($l-6));
			$l_len1	= strlen ($l1);
			$l2		= substr($str,$l_len1,($l-$l_len1-5));
			return	$l1.",".$l2." мБ";
		}
		if ($l < 3)
			return 0;

		return substr($str,0,($l-3)) . " кБ";
	}
}