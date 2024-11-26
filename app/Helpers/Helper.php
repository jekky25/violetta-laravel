<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PhotoRepository;

class Helper {
	/**
     * Create a new controller instance.
     *
     * @return void
     */			
    public function __construct(){
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
	 * @return \GDImage|resource|bool
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
	public static function delPhoto($photo)
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
		$user->user_fotos 			= (new PhotoRepository())->getAllByUserId($user->user_id)->count();
		$user->update();

		return true;
	}
}
