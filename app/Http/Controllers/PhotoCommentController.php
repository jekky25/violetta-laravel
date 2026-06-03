<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\PhotoCommentRequest;
use App\Services\PhotoCommentService;
use App\DTO\PhotoCommentDTO;


class PhotoCommentController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected PhotoCommentService $service
	) {}

	/**
	 * post pictures comments
	 * @param  PhotoCommentRequest  $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function store(PhotoCommentRequest $request, $id)
	{
		$dto = PhotoCommentDTO::fromRequest($request->validated());
		$this->service->create($id, auth()->user(), $dto);
		return redirect()->back()
			->with('success', 'Сообщение успешно отправлено')
			->withInput();
	}
}
