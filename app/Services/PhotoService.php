<?
namespace App\Services;

use App\Interfaces\VarsInterface;

class PhotoService
{
	protected $vars;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected VarsInterface $varsRepository
	)
	{
		$this->vars = $this->varsRepository->getAll();
	}

	/**
	* Make preparation of the picture paraments
	* @param  App\Models\User  $anket
	* @param  int $photoId
	* @param  int $perPage
	* @return void
	*/
	public function prepare(&$anket, $photoId, $perPage)
	{
		foreach ($anket->photo as &$item)
		{
			$item->comment	= $item->comment->slice(0, $perPage);
			if ($photoId > 0 && $item->fotos_id == $photoId)
				$anket->mainPhoto = $item;
		}
		$anket->mainPhoto			= $this->checkMainPhoto($anket);
		$anket->mainPhoto			= $this->addPictureParams($anket->mainPhoto);
	}

	/**
	* Check the main picture is it or not is it
	* @param  App\Models\User  $anket
	* @return bool
	*/
	public function checkMainPhoto($anket)
	{
		return !empty($anket->mainPhoto) ? $anket->mainPhoto : $anket->photo[0];
	}

	/**
	* Add additioanl parameters to the pictures
	* @param  App\Models\Photo  $photo
	* @return App\Models\Photo
	*/
	public function addPictureParams($photo)
	{
		$img 				= "./fotos_new/".$photo->fotos_id.".jpg";
		if (is_file($img))
		{
			$size = getimagesize($img);
			$photo->width	= $size [0] > $this->vars['max_foto_width_big'] ? $this->vars['max_foto_width_big'] : $size [0];
			$photo->url		= $img;
		}
		return $photo;
	}
}