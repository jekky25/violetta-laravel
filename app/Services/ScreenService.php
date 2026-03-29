<?php
namespace App\Services;

use App\Interfaces\ScreenInterface;
use App\Interfaces\CommentScreenInterface;
use App\DTO\ScreenSaverPageDTO;

class ScreenService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(
		private ScreenInterface $repository,
		private CommentScreenInterface $commentRepository
		) {}

	/**
	* get list of the screensavers
	*/
	public function getList(int $perPage)
	{
		return $this->repository->get($perPage);
	}

	/**
	* get data for the screensaver page
	*/
	public function showPage(int $id): ScreenSaverPageDTO
	{
		return new ScreenSaverPageDTO(
			screen:	$this->repository->getById($id),
			comments: $this->commentRepository->getByScrId($id)
		);
	}
}