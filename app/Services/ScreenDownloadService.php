<?php

namespace App\Services;

use App\Enums\ScreenSaverType;
use App\Interfaces\ScreenInterface;
use App\DTO\DownloadScreenDTO;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ScreenDownloadService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(
		private ScreenInterface $repository,
		) {}


	/**
	 * Download a screensaver
	 */
	public function download(DownloadScreenDTO $dto): BinaryFileResponse
	{
		$screenSaver	= $this->repository->getById($dto->screenId);
		$screenSaver->zakachka++;
		$this->repository->update($screenSaver);

		if ($dto->type == ScreenSaverType::VAR_SCR)
		{
			$path		= "screensavers/" . $screenSaver->path_scr;
			$fileName	= $screenSaver->name . ".scr";
		} else
		{
			$path		= "screensavers/" . $screenSaver->path_rar;
			$fileName	= $screenSaver->name . ".rar";
		}

		return response()->download($path, $fileName);
	}
}