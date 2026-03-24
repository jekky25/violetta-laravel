<?

namespace App\Services;

use App\Interfaces\DreamBookInterface;
use App\DTO\DreamBookDTO;

class DreamBookService
{
	public function __construct(
		private DreamBookInterface $repository
	) {}

	/**
	* get a list of a dreambook
	* @param  int $id
	* @return \Illuminate\Pagination\LengthAwarePaginator
	*/
	public function getList(int $id)
	{
		$paginator = $this->repository->get(config('pagination.dream_books'), $id);
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
	* @param  int $id
	* @return DreamBookDTO
	*/
	public function getItem(int $id)
	{
		$item = $this->repository->getById($id);
		$item->description = preg_replace('/sonnik_id([0-9]+).html/i', 'dreambook/$1.html',$item->description);
		return new DreamBookDTO(
            $item->id,
            $item->name,
            $item->description
        );
	}

	/**
	* get a list of literals
	* @return Collection
	*/
	public function getLiterals()
	{
		return $this->repository->getLiter();
	}
}
