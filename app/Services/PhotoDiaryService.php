<?

namespace App\Services;

use App\Interfaces\DiaryInterface;
use App\Services\FileService;
use App\Models\User;

class PhotoDiaryService
{
	public function __construct(
		protected DiaryInterface $diaryRepository,
		protected FileService $fileService
	) {}

	/**
	 * destroy a photo in the diary
	 */
	public function destroy(int $id, User $user): void
	{
		$diary			= $this->diaryRepository->getByUserAndId($id, $user->id);
		try {
			$data = [
				'picture' => 0
			];
			$this->fileService->remove($diary->picture_url);
			$this->diaryRepository->update($id, $data);
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Diary Comment Photo. '.$e->getMessage());
		}
	}
}