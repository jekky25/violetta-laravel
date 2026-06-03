<?
namespace App\Services;

use App\DTO\PhotoCommentDTO;
use App\Interfaces\CommentPhotoInterface;
use App\Models\User;

class PhotoCommentService
{
	protected $vars;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected CommentPhotoInterface $repository
	) {}

	/**
	 * create a comment
	 */
	public function create(int $photoId, User $user, PhotoCommentDTO $dto): void
	{
		$data = [
			'foto_id'					=> $photoId,
			'time'						=> now()->timestamp,
			'user_id'					=> $user->id,
			'description'				=> $dto->description
		];
		$this->repository->store($data);
	}
}