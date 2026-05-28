<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Requests\DiaryRequest;
use App\Services\DiaryService;
use App\DTO\DiaryDTO;

class DiaryController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct(
		protected DiaryService $service
	) {}

	/**
	 * show the page with diaries
	 * @return View
	 */
	public function index()
	{
		return view('diaries', ['diaries' => $this->service->index(config('pagination.diaries'))]);
	}

	/**
	 * Show an auth user diary page
	 * @return View
	 */
	public function myDiaries()
	{
		return view('registration.diary', ['diaries' => $this->service->myDiaries(config('pagination.diaries_user'), Auth()->user())]);
	}

	/**
	 * add an user diary
	 * @param  DiaryRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(DiaryRequest $request)
	{
		$dto = DiaryDTO::fromRequest($request->validated());
		$this->service->create($dto, auth()->user());
		return redirect()->back()
			->with('success', 'Дневник успешно добавлен')
			->withInput();
	}

	/**
	 * show an user diary page
	 * @param  int $id
	 * @return View
	 */
	public function show(int $id)
	{
		return view('ankets.diary', $this->service->getShowData(config('pagination.diaries_user'), $id));
	}

	/**
	 * show edit diary page
	 * @param  int $id
	 * @return View
	 */
	public function edit(int $id)
	{
		$user = Auth()->user();
		return view('ankets.diary_edit',
			[
				'userData'		=> $user,
				'diary'			=> $this->service->edit($id, $user)
			]
		);
	}

	/**
	 * update diary 
	 * @param  DiaryRequest  $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(DiaryRequest $request, int $id)
	{
		$dto = DiaryDTO::fromRequest($request->validated());
		$diary = $this->service->update($id, auth()->user(), $dto);
		return redirect()->route('ank.diary.id', $diary->user_id)
			->with('success', 'Дневник был обновлен')
			->withInput();
	}

	/**
	 * delete the user diary
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(int $id)
	{
		$diary = $this->service->destroy($id, request()->user());
		return redirect()->route('ank.diary.id', $diary->user_id);
	}
}
