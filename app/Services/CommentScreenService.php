<?php

namespace App\Services;

use App\DTO\CreateCommentScreenDTO;
use App\Interfaces\CommentScreenInterface;
use App\Interfaces\ScreenInterface;

class CommentScreenService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(
		private ScreenInterface $repository,
		private CommentScreenInterface $commentRepository,
		) {}

	/**
	 * Creste a new screen comment
	*/	
	public function store(CreateCommentScreenDTO $dto): void
	{
		$this->repository->getById($dto->screenId);
		$this->commentRepository->create([
			'scr_id' => $dto->screenId,
			'user_id' => $dto->userId,
    	    'create_time' => now(),
        	'name' => $dto->name,
	        'email' => $dto->email,
			'description' => $dto->description
		]);
	}
}