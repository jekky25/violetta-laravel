<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use \Illuminate\Support\Str;
use App\Services\FileService;

class ActivityItem extends Component
{
	public $item, $pictureSrc, $type, $routeDelete, $routeEdit;
	private static $strLimit = 40;
	private static $strBigLimit = 500;
	private static $strAddition = '...';
	private $service;

	/**
	 * Create a new component instance.
	*/
	public function __construct($item, $type = '')
	{
		$this->service	   = new FileService;
		$this->item        = $this->prepare($item);
		$this->type        = $type;
		$this->pictureSrc  = $this->getPictureSrc($item);
		[$this->routeEdit, $this->routeDelete] = $this->getRoutes($this->type);
	}

	/**
	 * prepare fields before output
	 * @param \Illuminate\Database\Eloquent\Collection $item
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	private function prepare($item)
	{
		$item->name_line_class	= 'line-' . $item->name_class;
		$item->title			= $this->cut($item->title, self::$strLimit);
		$item->description		= $this->cut($item->description, self::$strBigLimit);
		return $item;
	}

	/**
	 * cut string if it's too long
	 * @param string $str
	 * @param int $limit 
	 * @return string
	 */
	private function cut($str, $limit) 
	{
		return Str::limit($str, $limit, self::$strAddition);
	}

	/**
	 * get url of the main picture
	 * @param \Illuminate\Database\Eloquent\Collection $item
	 * @return string|null
	 */
	private function getPictureSrc($item)
	{
		if (!empty($item->picture)) 
			return $this->type == 'comment' 
				? $this->service->outDiaryCommentPicture($item->picture, $item->user->sex) 
				: $this->service->outDiaryPicture($item->picture, $item->user->sex);
		if (!empty($item->foto_user_id)) return $this->service->outPicture($item->foto_user_id, $item->user->sex);
		return null;
	}

	/**
	 * get route names for delete or edit
	 * @param string $type
	 * @return array
	 * 
	 * @throws \Exception
	 */
	private function getRoutes($type) {
		switch ($type) {
			case 'diary':
				return ['ank.diary.edit.id', 'ank.diary.delete.id'];
				break;
    		case 'comment':
				return ['ank.diary.comment.edit.id', 'ank.diary.comment.delete.id'];
        		break;
		}
		throw new \Exception('Тип блока activity-item указан не верно');
	}

	/**
	 * Get the view / contents that represent the component.
	*/
	public function render(): View|Closure|string
	{
		return view('components.activity.item');
	}
}