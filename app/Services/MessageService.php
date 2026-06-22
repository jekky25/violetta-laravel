<?php
namespace App\Services;

use App\Interfaces\UserInterface;
use App\Interfaces\MessageInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPrivMessageEmail;
use App\Models\User;
use App\Services\LengthPaginator;
use App\Interfaces\AnketEvaluationInterface;
use App\Interfaces\SmileInterface;

class MessageService
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected UserInterface $userRepository,
		protected MessageInterface $repository,
		protected AnketEvaluationInterface $anketEvaluationRepository,
		protected SmileInterface $smileRepository
	) {}

	/**
	 * get messages sorted by user
	*/
	public function getList(User $user): LengthPaginator
	{
		return $this->repository->getNewsByUsers($user, config('pagination.messages'));
	}

	/**
	 * get data for the show page
	 */
	public function getShowData(int $id, User $user, int $perPage)
	{
		$ankEvaluationed = $this->anketEvaluationRepository->getEvaluations($user->id, $id);
		return [
			'userData'			=> $user,
			'anketUserData'		=> $this->userRepository->getById($id),
			'ankEvaluationed' 	=> $ankEvaluationed->count() > 0 ? true : false,
			'messages'			=> $this->repository->getAllByUser($id, $user->id, $perPage),
			'smiles'			=> $this->smileRepository->getAll()
		];
	}

	/**
	* print information page with confirm or cancel
	* @param string $title
	* @param string $text
	* @param string $confirmAction 
	* @param string $hidden
	* @return \Illuminate\Http\Response
	*/
	public function outMessageInfo($title, $text, $confirmAction, $hidden = '')
	{
		return response()->view('mess_die.confirm',
		[
			'msgTitle' 		=> $title,
			'msgText'		=> $text,
			'confirmAction' => $confirmAction,
			'hidden'		=> $hidden
		])->send();
	}

	/**
	 * print information page with confirm or cancel
	 * @param string $title
	 * @param string $text
	 * @param string $confirmAction 
	 * @param string $hidden
	 * @return \Illuminate\Http\Response
	*/
	public function outMessageDie($title, $text, $hidden = '')
	{
		return response()->view('mess_die.info',
		[
			'msgTitle' 		=> $title,
			'msgText'		=> $text,
			'hidden'		=> $hidden
		])->send();
	}

	/**
	 * create a private message and send info about it to the user
	 * @param array $data
	 * @param integer $senderId
	 * @param integer $receiverId
	 * @return void
	*/
	public function create(array $data, int $senderId, int $receiverId)
	{
		$user = $this->userRepository->getJustById($senderId);
		$receiver = $this->userRepository->getJustById($receiverId);

		if (!$user || !$receiver) {
			abort(404);
		}

		$this->repository->store([
			'sent_user_id'		=> $senderId,
			'received_user_id'	=> $receiverId,
			'description'		=> $data['description'],
			'create_time'		=> now()->timestamp
		]);

		if ($receiver->dont_send_email != 1) {
			Mail::mailer(config('mail.mail_mode'))
				->to($receiver->email)
				->send(new NewPrivMessageEmail($receiver));
		}
	}

	/**
	 * remove a private message 
	*/
	public function destroy(int $id, User $user): void
	{
		$message = $this->repository->getByIdAndSenderOrReceiverId($id, $user->id);
		if (!$message) abort(404);
		$this->repository->delete($message, $user->id);
	}

	/**
	 * remove a few of private messages 
	*/
	public function destroyMany(array $data, User $user): void
	{
		$this->repository->deleteSelected($data['mark'], $user->id);
	}
}