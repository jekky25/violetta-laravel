<?php
namespace App\Services;

use App\Interfaces\ScreenInterface;
use App\Interfaces\CommentScreenInterface;
use App\DTO\ScreenSaverPageDTO;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheKeys;

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
        return Cache::remember(
			CacheKeys::screensList($perPage, request('page', 1)),
			config('cache_ttl.screens.all'),
            fn() => $this->repository->get($perPage)
        );
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