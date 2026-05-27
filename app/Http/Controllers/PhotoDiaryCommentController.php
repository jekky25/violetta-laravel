<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PhotoDiaryCommentService;

class PhotoDiaryCommentController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected PhotoDiaryCommentService $service
	) {}

	/**
	 * delete the picture of the diary comment
	 * @param  int $id
	 * @return void
	 */
	public function destroy(int $id)
	{
		$this->service->destroy($id, request()->user());
		return redirect()->route('ank.diary.comment.edit.id', $id);
	}
}
