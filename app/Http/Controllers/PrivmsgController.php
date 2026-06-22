<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\PrivmsgRequest;
use App\Requests\PrivmsgSelectedRequest;
use App\Services\MessageService;
use Illuminate\View\View;

class PrivmsgController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected MessageService $service
	) {}

	/**
	 * Show the application dashboard.
	 * @return View
	 */
	public function index()
	{
		return view('ankets.privmsg', [	'messages' => $this->service->getList(auth()->user()) ]);
	}

	/**
	 * Show a page with user messages
	 * @param  int $id
	 * @return View
	 */
	public function show(int $id)
	{
		return view('ankets.privmsg_id', $this->service->getShowData(
				$id, 
				request()->user()->load(['visits']),
				config('pagination.messages_profile')
				));
	}

	/**
	 * Add an user message
	 * @param  PrivmsgRequest $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function store(PrivmsgRequest $request, $id)
	{
		$this->service->create($request->validated(), auth()->id(), $id);
		return redirect()->back()
			->with('success', 'Сообщение успешно отправлено')
			->withInput();
	}

	/**
	 * Delete an user message
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(int $id)
	{
		$this->service->destroy($id, request()->user());
		return redirect()->back();
	}

	/**
	 * Delete user messages
	 * @param PrivmsgSelectedRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function destroyMany(PrivmsgSelectedRequest $request)
	{
		$this->service->destroyMany($request->post(), request()->user());
		return redirect()->route('privmsg');
	}
}
