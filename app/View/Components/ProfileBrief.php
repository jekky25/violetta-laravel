<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Services\FileService;

class ProfileBrief extends Component
{
	public $item, $best;
	private $file;
	/**
	 * Create a new component instance.
	 */
	public function __construct(FileService $file, $item, $best = 0)
	{
		$this->item = $item;
		$this->best = (int)$best;
		$this->file = $file;
	}

	/**
	* Get an id of the main picture of the profile
	* @return int
	*/
	public function getFirstPhotoId()
	{
		return !isset($this->item->firstPhoto->id) ? 0 : $this->item->firstPhoto->id;
	}

	/**
	* Get an url of the main picture of the profile
	* @return string
	*/
	public function getFirstPhotoUrl()
	{
		return $this->file->outPicture($this->getFirstPhotoId(), $this->item->user_sex);
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
    	return view('components.ankets.brief', [
													'photoId'	=> $this->getFirstPhotoId(),
													'photoUrl'	=> $this->getFirstPhotoUrl()
												]);
	}
}
