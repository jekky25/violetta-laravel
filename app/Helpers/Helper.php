<?php
namespace App\Helpers;
use Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Photo;
use App\Repositories\PhotoRepository;

class Helper {

	public static $zodiacSigns = [
				"1" => "Козерог",
				"2" => "Водолей",
				"3" => "Рыбы",
				"4" => "Овен",
				"5" => "Телец",
				"6" => "Близнецы",
				"7" => "Рак",
				"8" => "Лев",
				"9" => "Дева",
				"10" => "Весы",
				"11" => "Скорпион",
				"12" => "Стрелец"];

	/**
     * Create a new controller instance.
     *
     * @return void
     */			
    public function __construct(){
    }

	/**
	 * make range of ages
	 *
	 * @return array
	 */
    public static function getAges()
	{
		$items = [];
		for ($i= (17 + 1); $i < 100; $i++)
			$items[] = $i;
		return $items;
	}

	/**
	 * make days in the month
	 *
	 * @return array
	 */
	public static function getDays()
	{
		$items = [];
		for ($i=0; $i < 32;$i++) {
			$items [] = $i;
		}
		return $items;
	}

	/**
	 * make months of the year
	 *
	 * @return array
	 */
	public static function getMonths()
	{
		return [
			0 	=> '-выберите-',
			1 	=> 'Января',
			2 	=> 'Февраля',
			3 	=> 'Марта',
			4 	=> 'Апреля',
			5 	=> 'Мая',
			6 	=> 'Июня',
			7 	=> 'Июля',
			8 	=> 'Августа',
			9 	=> 'Сентября',
			10	=> 'Октября',
			11 	=> 'Ноября',
			12 	=> 'Декабря'
		];
	}

	/**
	 * make a list of years
	 *
	 * @return array
	 */
	public static function getYears()
	{
		$years 		= [];
		$today 		= getdate();
		$todayYear 	= $today['year'];
		
		for ($i=1900; $i < ($todayYear - 17);$i++) {
			$years [] = $i;
		}
		return $years;
	}

	/**
	 * make range of heights
	 *
	 * @return array
	 */
    public static function getHeights()
	{
		$items = [];
		for ($i= (PARTNER_HEIGHT_MIN + 1); $i < 221; $i++)
		{
  			$items[] = $i;
		}
		return $items;
	}

	/**
	 * make range of weights
	 *
	 * @return array
	 */
    public static function getWeights()
	{
		$items = [];
		for ($i= (PARTNER_WEIGHT_MIN + 1); $i < 131; $i++)
		{
  			$items[] = $i;
		}

		return $items;
	}

	/**
	 * count age
	 * @param $birth_date
	 *
	 * @return integer
	 */
    public static function age($birth_date)
	{
		preg_match("/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/",$birth_date,$pockets_old);
		$now_date 	= date("Y-m-d");
		preg_match("/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/",$now_date,$pockets_new);
		$old 		= $pockets_old[2].$pockets_old[3].$pockets_old[4];
		$new 		= $pockets_new[2].$pockets_new[3].$pockets_new[4];
  		$age 		= $new - $old;
  		$lenght 	= strlen($age);
  		$age_fin 	= substr($age,0,($lenght-4));
  		return $age_fin;
	}

	/**
	 * out age type
	 * @param $age
	 *
	 * @return string
	 */
	public static function ageType($age)
	{
  		$age = (int) $age;

  		if ($age >10 && $age <20)
    		$type = "лет";
  		else
  		{
			$age_fin = substr($age,-1,1);
			if ($age_fin <=0 )
    			$type = "лет";
			elseif ($age_fin ==1 )
				$type = "год";
			elseif ($age_fin >=2 && $age_fin<=4)
				$type = "года";
			else
				$type = "лет";
		}
  		return $type;
	}

	/**
	 * Type age from to
	 * @param $age
	 *
	 * @return string
	 */
	public static function ageType2($age)
	{
		$age = intval($age);
		if ($age >10 && $age <20)
			$type = "лет";
		else
		{
			$age_fin = substr($age,-1,1);
			if ($age_fin <=0 )
				$type = "лет";
			elseif ($age_fin ==1 )
				$type = "года";
			elseif ($age_fin >=2 && $age_fin<=4)
				$type = "лет";
			else
				$type = "лет";
		}
		return $type;
	}

	/**
	 * Out to xml
	 * @param $obj
	 *
	 * @return void
	 */
	public static function outToXml ($obj)
	{
		$startStr = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
		return response()
		->view ('xml.location', [
			'obj'		=> $obj,
			'startStr'		=> $startStr,
		])
		->header('Content-Type', 'text/xml')->send();
	}

	/**
	 * formate date from timestamp to date
	 * @param integer $timestamp
	 *
	 * @return string
	 */
	public static function getDate($timestamp = 0)
	{
		if ($timestamp == 0) $timestamp = time();
  		$date = date("d.m.y.",$timestamp);
		return $date;
	}

	/**
	 * out picture to template
	 * @param integer $picture
	 * @param string $sex
	 *
	 * @return string
	 */
	public static function outPicture($picture, $sex)
	{
		return self::getPicture ($picture, $sex, 'fotos_new/', '.jpg');
	}

	/**
	 * out picture in diaries to template
	 * @param integer $picture
	 * @param string $sex
	 *
	 * @return string
	 */
	public static function outDiaryPicture(string $picture, $sex)
	{
		return self::getPicture ($picture, $sex, 'img/dnevnik/', '');
	}

	/**
	 * out picture in comments of diaries to template
	 * @param integer $picture
	 * @param string $sex
	 *
	 * @return string
	 */
	public static function outDiaryCommentPicture(string $picture, $sex)
	{
		return self::getPicture ($picture, $sex, 'img/dnev_comment/', '');
	}

	/**
	 * out picture with storage path
	 * @param integer $picture
	 * @param string $sex
	 * @param string $path
	 * @param string $ext
	 *
	 * @return string
	 */
	public static function getPicture ($picture, $sex, $path, $ext)
	{
		$ext			= !empty ($ext) ? $ext : '';
		$file 			= $_SERVER['DOCUMENT_ROOT'] . '/public/' . $path . $picture . $ext;
		$fileTimeStr 	= !empty(self::getFileChangeTime($file)) 	? self::getFileChangeTime($file) . '/'		: '';
		if (!empty($picture) && !empty($path)) return asset($path . $fileTimeStr . $picture . $ext);
		$fotoUrl = $sex == MEN ? 'image/no_foto_m_vip.jpg' : 'image/no_foto_w_vip.jpg';
		return asset ($fotoUrl);
	}

	/**
	 * getting file changins time
	 * @param string $file
	 *
	 * @return string
	 */
	public static function getFileChangeTime (string $file)
	{
		return file_exists($file) 	? filemtime($file)		: '';
	}

	/**
	 * preparation pagination to out to the template
	 * @param array $paginagion
	 *
	 * @return array
	 */
	public static function preparePagination($pagination)
	{
		if (empty ($pagination)) return [];

		$pagination[0] 		= str_replace (' Previous','', $pagination[0]);
		$pagination[0] 		= str_replace ('&laquo;','&lt;', $pagination[0]);
		$ind 				= count ($pagination) - 1;
		$pagination[$ind] 	= str_replace ('Next ','', $pagination[$ind]);
		$pagination[$ind] 	= str_replace ('&raquo;','&gt;', $pagination[$ind]);

		return $pagination;
	}

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
    		$l1 	= substr($str,0,($l-6));
    		$l_len1 = strlen ($l1);
    		$l2 	= substr($str,$l_len1,($l-$l_len1-5));
			$l		= $l1.",".$l2." мБ";
		}
		else
		{
			if ($l < 3)
				$l1 = 0;
			else
				$l1 = substr($str,0,($l-3));
			$l = $l1." кБ";
		}
		return $l;
	}

	/**
	 * returns the date by age
	 *
	 * @return Carbon\Carbon
	 */
	public static function birthAround($age)
	{
		$time = Carbon\Carbon::now()->add(-$age, 'year')->format('Y-m-d');
		return $time;
	}

	/**
	 * make block <select>...</select>
	 * @param string $name
	 * @param string $className
	 * @param integer $value
 	 * @param integer $mode
	 *
	 * @return string
	 */
	public static function BlockSelect($name,$className,$value,$mode)
	{
		if (!is_array ($value))
		{
			try {
				$unserValue = unserialize($value);
			} catch (\Exception $e) {}
		}

		$value		= isset ($unserValue) && is_array ($unserValue) ? $unserValue : $value;
		$className = 'App\\Models\\' . $className;
		$items = $className::select('*')->orderBy('name','asc')->get();
		$str 	= '<select style="width: 150px" name="' . $name . '">';
		$str 	.= '<option value="0"';
		$str	.= $value == '0' 	? ' selected' 		: '';
		$str	.= $mode == 1 		? '>-выберите-' 	: '>-не важно-';

		if ($mode == 2)
		{
			$out = [];
			foreach ($items as $_item)
			{
				$_item->selected = is_array($value) ? (in_array($_item->id, $value) 	? 1 			: 0)
													: ($value == $_item->id 			? ' selected' 	: '');
				$out [] = $_item;
			}
			return $out;
		}


		if (!empty ($items))
		{
			foreach ($items as $_item)
			{
				$str .= '  <option value="' . $_item->id . '"';
				$str .= $value == $_item->id ? ' selected' : '';
				$str .= '>' . $_item->name;
			}
		}
		$str .= '</option></select>';
		return $str;
	}

	/**
	 * make query Block
	 * @param array $ar
	 * @param object $items
	 *
	 * @return void
	 */
	public static function queryBlock($ar, &$items)
	{
		$items->where (function ($query) use ($ar) {
			$query->where('user_sex', $ar[0]);
			$query->where(function ($query) use ($ar) {
				$query->where('user_sex_orient', $ar[1]);
				$query->orWhere('user_sex_orient', $ar[2]);
			});
		});
	}

	/**
	 * make or query Block
	 * @param array $ar
	 * @param object $items
	 *
	 * @return void
	 */
	public static function queryBlockOr($ar, &$items)
	{
		$items->Orwhere (function ($query) use ($ar) {
			$query->where('user_sex', $ar[0]);
			$query->where(function ($query) use ($ar) {
				$query->where('user_sex_orient', $ar[1]);
				$query->orWhere('user_sex_orient', $ar[2]);
			});
		});
	}

	/**
	 * make found string
	 * @param object $ankets
	 * @param integer $count
	 *
	 * @return string
	 */
	public static function getFoundStr($ankets, $count = 0)
	{
		$startShow 		= (($ankets->currentPage() - 1) * $count) + 1;
		$endShow		= $ankets->currentPage() * $count;
		$endShow		= $endShow < $ankets->total() ? $endShow : $ankets->total();
				
		$str = 'Найдено анкет: (' . $startShow . '-' . $endShow . ') из ' . $ankets->total();
		return $str;
	}

	/**
	 * make last visit
	 * @param integer $time
	 *
	 * @return string
	 */
	public static function lastVisit($time)
	{
		$timestamp = time();
  		$dateCheck = date("d-m-y",$time);
  		$timeCheck = date("d-m-y",$timestamp);
  		$timeCheckIs = date("d-m-y",$timestamp- (60*60*24));
  		if ($dateCheck == $timeCheck)
  		{
    		$date = date("Сегодня",$time);
  		} else if ($dateCheck == $timeCheckIs)
  		{
    		$date = date("Вчера",$time);
  		}
   		else
    		$date = date("d.m.y.",$time);
  		return $date;
	}

	/**
	 * out diary type in cases
	 * @param integer $number
	 *
	 * @return string
	*/
	public static function caseDiaryType($number)
	{
		$number = intval($number);
		if ($number >10 && $number <20)
			$type = "записей";
		else
		{
			$number_fin = substr($number,-1,1);
			if ($number_fin <=0 )
				$type = "записей";
			elseif ($number_fin ==1 )
				$type = "запись";
			elseif ($number_fin >=2 && $number_fin<=4)
				$type = "записи";
			else
				$type = "записей";
		}
		return $type;
	}

	/**
	 * get zodiac name by birth date
	 * @param string $birth_date
	 *
	 * @return string
	*/
	public static function zodiac($birthDate)
	{
	
		$arZodiac = [];
		$zodiac = self::$zodiacSigns;
		preg_match("/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/",$birthDate,$pockets);

		$pockets[3] = (int)$pockets[3];
		$pockets[4] = (int)$pockets[4];
		switch ($pockets[3]) {
			case '01':
			case '03':
			case '04':
				$id	= $pockets[4] <= 20 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '02':
				$id	= $pockets[4] <= 19 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '05':
			case '06':	
				$id	= $pockets[4] <= 21 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '07':
			case '08':
			case '09':
			case '10':
				$id	= $pockets[4] <= 23 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '11':
				$id	= $pockets[4] <= 22 ? $pockets[3] : ($pockets[3] + 1);
			break;

			case '12':
				$id	= $pockets[4] <= 22 ? $pockets[3] : 1;
			break;

			default:
				$id = 1;
			break;
		}


		$arZodiac['zodiac_text'] = $zodiac[$id];

		switch ($id) {
			case 1:
				$arZodiac['zodiac_id'] = 10;
			break;
			case 2:
				$arZodiac['zodiac_id'] = 11;
			break;
			case 3:
				$arZodiac['zodiac_id'] = 12;
			break;
			case 4:
				$arZodiac['zodiac_id'] = 1;
			break;
			case 5:
				$arZodiac['zodiac_id'] = 2;
			break;
			case 6:
				$arZodiac['zodiac_id'] = 3;
			break;
			case 7:
				$arZodiac['zodiac_id'] = 4;
			break;
			case 8:
				$arZodiac['zodiac_id'] = 5;
			break;
			case 9:
				$arZodiac['zodiac_id'] = 6;
			break;
			case 10:
				$arZodiac['zodiac_id'] = 7;
			break;
			case 11:
				$arZodiac['zodiac_id'] = 8;
			break;
			default:
				$arZodiac['zodiac_id'] = 9;
			break;

		}
		return $arZodiac;
	}

	/**
	 * move date to the format dd.mm.yyyy
	 * ex date_format ($date)
	 * @param string $date
	 *
	 * @return string
	*/
	public static function dateFormat($date)
	{
		preg_match("/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/",$date,$pockets);
		$date_out = $pockets[4].".".$pockets[3].".".$pockets[2];
		return $date_out;
	}

	/**
	 * get reiting string
	 * @param string $birth_date
	 *
	 * @return string
	*/
	public static function reiting($reit, $maxReit)
	{
		$reiting = round(($reit / $maxReit ) * 1000);
		$reitStr = $reiting / 100;
		if ($reitStr > 7) 
			$str = 70;
		elseif ($reitStr > 5) 
			$str = 56;
		elseif ($reitStr > 3) 
			$str = 42;
		elseif ($reitStr > 2) 
			$str = 28;
		elseif ($reitStr > 1)
			$str = 14;
		else 
			$str = 7;

		return $str;
	}

	/**
	 * adding pictures
	 * @param object $picture
	 * @param integer $foto
	 * @param string $path_foto
	 *
	 * @return mixed
	*/
	public static function fotoUpload($picture, $width = 0, $path_foto = '') 
	{
		$photo['link']		= !empty($picture->getPathName())					? $picture->getPathName() 								: false;
		$photo['name']		= !empty($picture->getClientOriginalName())			? $picture->getClientOriginalName()						: '';
		$photo['size']		= !empty($picture->getSize())						? $picture->getSize()									: 0;
		$photo['filetype']	= !empty($picture->getMimeType())					? $picture->getMimeType()								: '';
		$photo['extension']	= !empty($picture->getClientOriginalExtension())	? strtolower($picture->getClientOriginalExtension()) 	: 'jpg';
		$width 				= $width > 0 ? $width : 200;
		$photo['unic_name']	= Str::random(20) . '.' . $photo['extension'];
		$photoLink			= $path_foto . (!empty($picture->nameForInsert) ? $picture->nameForInsert : $photo['unic_name']);
		$photoLinkTmp		= 'img_temp/' . $photo['unic_name'];
		$res = Helper::moveUploadedFile($photo['link'], $photoLinkTmp);
		$res = Helper::resize($photoLinkTmp, $width, $photoLink);
		unlink($photoLinkTmp);
		return isset($res ['success']) ? $photo['unic_name'] : false;
	}

	/**
	 * moving uploaded file
	 * @param string $link
	 * @param string $name
	 *
	 * @return bool
	*/
	public static function moveUploadedFile($link, $name)
	{
		return move_uploaded_file($link, $name);
	}

	/**
	 * resizes uploaded files
	 * @param string $file
	 * @param integer $width
	 * @param string $destination_file
	 *
	 * @return array
	*/
	public static function resize ($file, $width = 0, $destination_file = null)
	{
		$width = (int) $width;

		$srcImg = Helper::read($file);
		if(!$srcImg) return ['error' => 'Файл не является изображением'];

		$srcWidth 	= imagesx($srcImg);
		$srcHeight 	= imagesy($srcImg);
		if(!$width) $width = $srcWidth;
		$ratio 		= $srcWidth/$srcHeight;
		$width 		= $srcWidth > $width ? $width :$srcWidth;
		$height 	= round($width / $ratio, 0);
		if($width == $srcWidth){//skip image resize
			if(!copy($file, $destination_file))
			{
				return array ('error' => 'Ошибка записи файла');
			}
			return null;
		}
		$dstImg = imagecreatetruecolor( $width, $height );
		if ( empty($dstImg) ) {
			@imagedestroy( $srcImg );
			return 'Error creating true color image {$width}&times;{$height}';
		}

		if ( function_exists('imagecopyresampled') ) 
			$res = @imagecopyresampled ( $dstImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight );
		else
			$res = @imagecopyresized ( $dstImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight );

		$quality = 80;

		$res = @imagejpeg( $dstImg, !is_null($destination_file) ? $destination_file : $file, $quality);

		if (empty($res)) 
		{
			@imagedestroy( $srcImg );
			@imagedestroy( $dstImg );
	
			return 'Error copy resized image';
		} else
		{
			@imagedestroy( $dstImg );
			@imagedestroy( $srcImg );
			return ['success' => 1];
		}
	}

	/**
	 * creates image from the file
	 * @param string $fileName
	 *
	 * @return resource|bool
	*/
	public static function read($fileName)
	{
		if(!file_exists($fileName))
		{
			return 'Error upload file';
		}
		$info = @getimagesize($fileName);
		switch ($info[2]) 
		{
			case 1:
				// Create recource from gif image
				$srcIm = @imagecreatefromgif( $fileName );
				break;
			case 2:
				// Create recource from jpg image
				$srcIm = @imagecreatefromjpeg( $fileName );
				break;
			case 3:
				// Create resource from png image
				$srcIm = @imagecreatefrompng( $fileName );
				break;
			case 5:
				// Create resource from psd image
				break;
			case 6:
				// Create recource from bmp image imagecreatefromwbmp
				$srcIm = @imagecreatefromwbmp( $fileName );
				break;
			case 7:
				// Create resource from tiff image
				break;
			case 8:
				// Create resource from tiff image
				break;
			case 9:
				// Create resource from jpc image
				break;
			case 10:
				// Create resource from jp2 image
				break;
			default:
				break;
		}
	
		return !empty ($srcIm) ? $srcIm : false;
	}

	/**
	 * print information page with confirm or cancel
	 * @param string $title
	 * @param string $text
	 * @param string $confirmAction 
	 * @param string $hidden
	 *
	 * @return \Illuminate\Http\Response
	*/
	public static function outMessageInfo($title, $text, $confirmAction, $hidden = '')
	{
		return response()->view ('mess_die.confirm',
		[
			'msgTitle' 		=> $title,
			'msgText'		=> $text,
			'confirmAction' => $confirmAction,
			'hidden'		=> $hidden
		])->send();
	}

	/**
	 * print information page with confirm or cancel
	 * @param string $title
	 * @param string $text
	 * @param string $confirmAction 
	 * @param string $hidden
	 *
	 * @return \Illuminate\Http\Response
	*/
	public static function outMessageDie($title, $text, $hidden = '')
	{
		return response()->view ('mess_die.info',
		[
			'msgTitle' 		=> $title,
			'msgText'		=> $text,
			'hidden'		=> $hidden
		])->send();
	}

	/**
	 * translate smiles from codes to html tages
	 * 
	 * @param string $str
	 * @param array $smiles
	 *
	 * @return string
	 */
	public static function transformSmiles ($str, $smiles)
	{
		if (empty ($smiles)) return $str;
		foreach ($smiles as $_smile) {
			$str = str_replace ($_smile->smile_code, '<img class="messBSmile" src="' . asset('image/smiles/' . $_smile->smile_img) . '" alt="" />', $str);
		}
		return $str;
	}

	/**
	 * select a separate year, month or day from the date
	 * 
	 * @param string $date
	 * @param integer $mode
	 *
	 * @return array
	 */
	public static function selectFromDate($date,$mode)
	{
  		preg_match("/^ *(([0-9]+)-([0-9]+)-([0-9]+)) *$/",$date,$pockets_old);
  		return $pockets_old[$mode];
	}

	/**
	 * transform day, month and year to data string format 0000-00-00
	 * 
	 * @param integer $day
	 * @param integer $month
	 * @param integer $year
	 *
	 * @return string
	 */
	public static function getDateStr($day,$month,$year)
	{
		$day 	= $day < 10 	? "0$day" 	: $day;
		$month 	= $month < 10 	? "0$month" : $month;
		return "$year-$month-$day";
	}

	/**
	 * serialize array from the input fields
	 * 
	 * @param array $data
	 *
	 * @return string
	 */
	public static function serializeInput($data)
	{
		return !is_array($data) ? '' : serialize ($data);
	}


	/**
	 * remove the picture from the server and makes update about it in the DB
	 * 
	 * @param App\Models\Photo $photo
	 *
	 * @return bool
	 */
	public static function delPhoto ($photo)
	{
		$user 			= Auth::user();
		$id 			= $photo->fotos_id;

		if (file_exists("fotos_new/".$id.".jpg")) {
			if(unlink("fotos_new/".$id.".jpg")) {}
		}

		$isPortret = $photo->fotos_portret == 1 ? 1 : 0;
		$photo->delete();

		if ($isPortret)
		{
			$photo = (new PhotoRepository())->getFirstByUserId($user->user_id);
			if (!empty($photo))
			{
				$photo->fotos_portret = 1;
				$photo->update();
			}
		}

		$user->user_refresh_date 	= date("Y-m-d");
		$user->user_refresh_date_t 	= time();
		$user->user_session_time 	= time();
		$user->user_lastvisit 		= time();
		$user->user_fotos 			= count (Photo::getAllByUserId($user->user_id));
		$user->update();

		return true;
	}
}
