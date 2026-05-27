<?

namespace App\Services;

use App\Interfaces\DiaryCommentInterface;
use App\Services\FileService;
use App\Models\User;

class PhotoDiaryCommentService
{
	public function __construct(
		protected DiaryCommentInterface $diaryCommentRepository,
		protected FileService $fileService
	) {}

	/**
	 * destroy a photo in the comment of the diary
	 */
	public function destroy(int $id, User $user): void
	{
		$comment = $this->diaryCommentRepository->getByUserAndId($id, $user->id);
		try {
			$data['picture'] = 0;
			$this->fileService->remove($comment->picture_url);
			$this->diaryCommentRepository->update($id, $data);
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Diary Comment Photo. '.$e->getMessage());
		}
	}
}