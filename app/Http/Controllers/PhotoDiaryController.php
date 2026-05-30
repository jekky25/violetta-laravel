<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PhotoDiaryService;

class PhotoDiaryController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct(
		protected PhotoDiaryService $service
	) {}

	/**
	 * delete the picture of the diary
	 * @param  int $id
	 * @return void
	 */
	public function destroy(int $id)
	{
		$this->service->destroy($id, request()->user());
		return redirect()->route('ank.diary.edit.id', $id);
	}
}
