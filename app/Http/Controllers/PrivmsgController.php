<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\AnketEvaluationInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\MessageInterface;
use App\Interfaces\SmileInterface;
use App\Requests\PrivmsgRequest;
use App\Requests\PrivmsgSelectedRequest;
use App\Services\MessageService;

class PrivmsgController extends Controller
{
	public static $messagePerPage 		= 10;
	public static $messageAnkPerPage 	= 30;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected AnketEvaluationInterface $anketEvaluationRepository,
		protected MessageInterface $messageRepository,
		protected SmileInterface $smileRepository,
		protected MessageService $messageService,
		protected UserInterface $userRepository
	) {}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user 			= Auth::user();
		$messages 			= $this->messageRepository->getAll($user->id, self::$messagePerPage);
		$messages			= $this->messageRepository->getNewsByUsers($messages, $user);
		return response()->view(
			'ankets.privmsg',
			[
				'messages' 		=> $messages
			]
		);
	}

	/**
	 * Delete user messages
	 * @param PrivmsgSelectedRequest $request
	 * @return void
	 */
	public function destroySelected(PrivmsgSelectedRequest $request)
	{
		$arParams 		= $request->post();
		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить сообщения этих пользователей?<br /><br />';
		$confirmAction 	= route('privmsg.delete');
		$hidden			= method_field('DELETE');

		foreach ($arParams['mark'] as $item) {
			$hidden .= '<input type="hidden" name="mark[]" value="' . intval($item) . '" />';
		}
		$this->messageService->outMessageInfo($title, $text, $confirmAction, $hidden);
	}

	/**
	 * Delete user messages
	 * @param PrivmsgSelectedRequest $request
	 * @return void
	 */
	public function destroySelectedAction(PrivmsgSelectedRequest $request)
	{
		$user 			= Auth::user();
		$arParams 		= $request->post();
		if (!empty($arParams['cancel'])) return redirect()->route('privmsg');
		if (!empty($arParams['confirm'])) {
			$this->messageRepository->deleteSelected($arParams['mark'], $user->id);
			return redirect()->route('privmsg');
		}
	}

	/**
	 * Delete an user message
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$this->messageRepository->getById($id);
		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить это сообщение?<br /><br />';
		$confirmAction 	= route('privmsg.post.delete', $id);
		$this->messageService->outMessageInfo($title, $text, $confirmAction, method_field('DELETE'));
	}

	/**
	 * Delete an user message
	 * @param  PrivmsgRequest $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroyAction(PrivmsgRequest $request, $id)
	{
		$user 			= Auth::user();
		$message 		= $this->messageRepository->getById($id);
		$arParams 		= $request->post();
		$userId 		= $message->sent_user_id == $user->id ? $message->received_user_id : $message->sent_user_id;
		if (!empty($arParams['cancel'])) return redirect()->route('privmsg.post', $userId);
		if (!empty($arParams['confirm'])) {
			$this->messageRepository->delete($message, $user->id);
			return redirect()->route('privmsg.post', $userId);
		}
	}

	/**
	 * Show a page with the user messages
	 * @param  Request $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function getAnkMess(Request $request, $id)
	{
		$user 			= $request->user()->load(['visits']);
		$anket 			= $this->userRepository->getById($id);
		$messages 		= $this->messageRepository->getAllByUser($id, $user->id, self::$messageAnkPerPage);
		$smiles			= $this->smileRepository->getAll();
		$this->anketEvaluationRepository->getEvaluations($user->id, $id);
		$ankEvaluationed = $this->anketEvaluationRepository->getEvaluationWithUpdate($request, $user->id, $id);
		if (is_object($ankEvaluationed) && get_class($ankEvaluationed) == 'Illuminate\Http\RedirectResponse') return $ankEvaluationed;
		return response()->view(
			'ankets.privmsg_id',
			[
				'userData'			=> $user,
				'anketUserData'		=> $anket,
				'ankEvaluationed' 	=> isset($ankEvaluationed) ? $ankEvaluationed : false,
				'messages'			=> $messages,
				'smiles'			=> $smiles
			]
		);
	}

	/**
	 * Add an user message
	 * @param  PrivmsgRequest $request
	 * @param  int $id
	 * @return void
	 */
	public function store(PrivmsgRequest $request, $id)
	{
		$this->messageService->create($request->validated(), auth()->id(), $id);
		return redirect()->back()
			->with('success', 'Сообщение успешно отправлено')
			->withInput();
	}
}
