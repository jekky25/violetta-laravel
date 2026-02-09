<?
namespace App\Services;

use Illuminate\Support\Str;

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

	/**
	 * adding pictures
	 * @param object $picture
	 * @param integer $foto
	 * @param string $path_foto
	 *
	 * @return mixed
	*/
	public function fotoUpload($picture, $width = 0, $path_foto = '') 
	{
		$photo['link']		= !empty($picture->getPathName())					? $picture->getPathName() 								: false;
		$photo['name']		= !empty($picture->getClientOriginalName())			? $picture->getClientOriginalName()						: '';
		$photo['size']		= !empty($picture->getSize())						? $picture->getSize()									: 0;
		$photo['filetype']	= !empty($picture->getMimeType())					? $picture->getMimeType()								: '';
		$photo['extension']	= !empty($picture->getClientOriginalExtension())	? strtolower($picture->getClientOriginalExtension()) 	: 'jpg';
		$width				= $width > 0 ? $width : 200;
		$photo['unic_name']	= Str::random(20) . '.' . $photo['extension'];
		$photoLink			= $path_foto . (!empty($picture->nameForInsert) ? $picture->nameForInsert : $photo['unic_name']);
		$photoLinkTmp		= 'img_temp/' . $photo['unic_name'];
		$res				= $this->moveUploadedFile($photo['link'], $photoLinkTmp);
		$res				= $this->resize($photoLinkTmp, $width, $photoLink);
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
	public function moveUploadedFile($link, $name)
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
	public function resize($file, $width = 0, $destination_file = null)
	{
		$width = (int) $width;

		$srcImg		= $this->read($file);
		if(!$srcImg) return ['error' => 'Файл не является изображением'];

		$srcWidth	= imagesx($srcImg);
		$srcHeight	= imagesy($srcImg);
		if(!$width) $width = $srcWidth;
		$ratio		= $srcWidth/$srcHeight;
		$width		= $srcWidth > $width ? $width :$srcWidth;
		$height		= round($width / $ratio, 0);
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
	 * @return \GDImage|resource|bool|
	*/
	public function read($fileName)
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
	 * remove file
	 * @param string $fileName
	 *
	 * @return bool
	*/
	public function remove($fileName)
	{
		if (!empty($fileName) && file_exists($fileName))
		{
			return unlink($fileName);
		}
		return false;
	}

	/**
	* out picture to template
	* @param integer $picture
	* @param string $sex
	*
	* @return string
	*/
	public function outPicture($picture, $sex = 0)
	{
		return $this->getPicture($picture, $sex, 'fotos_new/', '.jpg');
	}

	/**
	* out picture in diaries to template
	* @param integer $picture
	* @param string $sex
	*
	* @return string
	*/
	public function outDiaryPicture(string $picture, $sex)
	{
		return $this->getPicture($picture, $sex, 'img/dnevnik/', '');
	}

	/**
	* out picture in comments of diaries to template
	* @param integer $picture
	* @param string $sex
	*
	* @return string
	*/
	public function outDiaryCommentPicture(string $picture, $sex)
	{
		return $this->getPicture($picture, $sex, 'img/dnev_comment/', '');
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
	public function getPicture($picture, $sex, $path, $ext)
	{
		$ext			= !empty($ext) ? $ext : '';
		$file 			= $_SERVER['DOCUMENT_ROOT'] . '/public/' . $path . $picture . $ext;
		$fileTimeStr 	= !empty($this->getFileChangeTime($file)) 	? $this->getFileChangeTime($file) . '/'		: '';
		if (!empty($picture) && !empty($path)) return asset($path . $fileTimeStr . $picture . $ext);
		$fotoUrl = $sex == MEN ? 'image/no_foto_m_vip4.jpg' : 'image/no_foto_w_vip4.jpg';
		return asset($fotoUrl);
	}

	/**
	* getting file changins time
	* @param string $file
	*
	* @return string
	*/
	public function getFileChangeTime(string $file)
	{
		return file_exists($file) ? filemtime($file)	: '';
	}

	public function fotoDelete($id)
	{
		if (file_exists("fotos_new/".$id.".jpg")) {
			unlink("fotos_new/".$id.".jpg");
			return true;
		}
		return false;
	}
}