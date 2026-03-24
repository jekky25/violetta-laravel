<?php

namespace App\Services;

use App\Interfaces\DreamBookInterface;
use App\DTO\DreamBookDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DreamBookService
{
	public function __construct(
		private DreamBookInterface $repository
	) {}

	/**
	* get a list of a dreambook
	*/
	public function getList(int $literId, int $perPage): LengthAwarePaginator
	{
		$paginator = $this->repository->get($perPage, $literId);
		$paginator->getCollection()->transform(function ($item) {
			return new DreamBookDTO(
				$item->id,
				$item->name,
				$item->description
			);
		});
		return $paginator;
	}

	/**
	* get an item
	*/
	public function getItem(int $id): DreamBookDTO
	{
		$item = $this->repository->getById($id);
		return new DreamBookDTO(
            $item->id,
            $item->name,
            $item->description
        );
	}

	/**
	* get a list of literals
	*/
	public function getLiterals(): Collection
	{
		return $this->repository->getLiter();
	}
}
