<?
namespace App\Services;

use App\DTO\CreatePhotoDTO;
use App\Interfaces\VarsInterface;
use App\Interfaces\UserInterface;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Photo;
use App\Repositories\PhotoRepository;
use App\Interfaces\AnketVisitInterface;
use App\DTO\UpdatePhotoDTO;

class PhotoService
{
	protected $vars;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected VarsInterface $varsRepository,
		protected UserInterface $userRepository,
		protected PhotoRepository $photoRepository,
		protected AnketVisitInterface $anketVisitRepository,
		protected FileService $fileService
	)
	{
		$this->vars = $this->varsRepository->getAll();
	}

	/**
	 * get all photos for the user
	 */
	public function allByUser(User $user): Collection|null
	{
		$user = $this->userRepository->getJustById($user->id, ['photo']);
		return $user->photo->isEmpty() ? null : $user->photo;
	}

	/**
	* Get the main picture is it or not is it
	*/
	public function getMainPhoto(int $userId): ?Photo
	{
		return $this->photoRepository->getMainPhotoByUserId($userId);
	}

	/**
	 * get data for the Photo Page
	 */
	public function getPhotoPageData(int $id, User $authUser): array
	{
		$photo	= $this->photoRepository->getById($id);

		if (!$photo) abort(404);
		$user = $this->userRepository->getById($photo->user_id);
		$user->ankVisits	= $this->anketVisitRepository->update($user->id, config('profile.visit_days'), $authUser->id);
		return [
			'photo' => $photo,
			'userData' => $user
			];
	}

	/**
	 * create a photo
	 */
	public function create(User $user, CreatePhotoDTO $dto): void
	{
		try {
			$uploaded = $this->fileService->fotoUpload($dto->photo, 1000, config('photos.folder'));
			$user->refresh_date		= now()->toDateString();
			$user->save();
			$data = [
				'main_picture'				=> $user->photo->count() > 0 ? 0 : 1,
				'create_time'				=> now()->toDateString(),
				'user_id'					=> $user->id,
				'path'						=> $uploaded['unic_name']
			];
			$photo = $this->photoRepository->create($data);
			if ($photo === null ) throw new \Exception('Failed to create a Photo');
	
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Photo ' . $e->getMessage());
		}
	}

	/**
	 * edit a photo
	 */	
	public function edit(int $id, User $user): Photo
	{
		return $this->photoRepository->getByIdAndUserId($id, $user->id);
	}

	/**
	 * update a photo
	 */
	public function update(int $id, User $user, UpdatePhotoDTO $dto): void
	{
		try {
			$photoModel	= $this->photoRepository->getByIdAndUserId($id, $user->id);
			$photo = $this->fileService->fotoUpload($dto->photo, 1000, config('photos.folder'));
			$this->fileService->fotoDelete($photoModel->full_path);

			$user->refresh_date		= now()->toDateString();
			$user->update();
			$data = [
				'create_time'				=> now()->toDateString(),
				'path'						=> $photo['unic_name']
			];
			$this->photoRepository->update($photoModel, $data);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update the Photo ' . $e->getMessage());
		}
	}

	/**
	 * destroy a photo
	 */
	public function destroy(int $id, User $user): void
	{
		try {
			$photo	= $this->photoRepository->getByIdAndUserId($id, $user->id);
			$isPortret = $photo->main_picture == 1 ? 1 : 0;
			$this->fileService->fotoDelete($photo->full_path);
			$this->photoRepository->destroyPhoto($photo);
			if ($isPortret) {
				$this->setMainPicture($user);
			}
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete the Photo ' . $e->getMessage());
		}
	}

	/**
	 * set a main picture value in the DB
	 */
	public function setMainPicture(User $user): bool
	{
		$photo = $this->photoRepository->getFirstByUserId($user->id);
		if (empty($photo)) return false;

		$photo->main_picture = 1;
		$photo->update();
		
		$user->refresh_date 		= now()->toDateString();
		$user->photos_count = $this->photoRepository->getAllByUserId($user->id)->count();
		$user->update();
		return true;
	}

	/**
	* Make preparation of the picture paraments
	*/
	public function prepare(User &$anket, int $photoId): void
	{
		foreach ($anket->photo as &$item)
		{
			$item->comment	= $item->comment->slice(0, config('pagination.comments_photo'));
			if ($photoId > 0 && $item->id == $photoId)
				$anket->mainPhoto = $item;
		}
		$anket->mainPhoto			= $this->getMainPhoto($anket->id);
	}
}