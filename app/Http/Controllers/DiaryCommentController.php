<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\DiaryCommentRequest;
use App\Services\DiaryCommentService;
use App\DTO\DiaryCommentDTO;
use Illuminate\View\View;

class DiaryCommentController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected DiaryCommentService $service
	) {}

	/**
	 * show a comments diary page
	 * @param  int $id
	 * @return View
	 */
	public function index(int $id)
	{
		return view('ankets.comments', $this->service->getIndexData($id, config('pagination.comments_diary')));
	}

	/**
	 * show an edit comment page and update the comment
	 * @param  int $id
	 * @return View
	 */
	public function edit(int $id)
	{
		return view('ankets.diary_comment_edit', 
		[
			'comment'	=> $this->service->edit($id),
			'userData'	=> Auth()->user()
		]);
	}

	/**
	 * add a comment of the diary
	 * @param  DiaryCommentRequest $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function store(DiaryCommentRequest $request, int $id)
	{
		$dto = DiaryCommentDTO::fromRequest(
			$id,
			$request->user(),
			$request->validated()
		);
		$this->service->create($dto);
		return redirect()->back()
						->with('success','Комментарий успешно добавлен')
						->withInput();
	}

	/**
	 * update an edit comment page and update the comment
	 * @param  DiaryCommentRequest $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(DiaryCommentRequest $request, $id)
	{
		$dto = DiaryCommentDTO::fromRequest(
			$id,
			$request->user(),
			$request->validated()
		);
		$comment = $this->service->update($id, request()->user(), $dto);
		return redirect()->route('ank.diary.comments', $comment->diary_id)
			->with('success', 'Комментарий был обновлен')
			->withInput();
	}

	/**
	 * delete a comment of the diary
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$comment = $this->service->destroy($id, request()->user());
		return redirect()->route('ank.diary.comments', $comment->diary_id)->with('success', 'Информация сохранена.');
	}
}