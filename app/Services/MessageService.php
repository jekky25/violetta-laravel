<?
namespace App\Services;

use App\Interfaces\UserInterface;
use App\Interfaces\MessageInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPrivMessageEmail;
use App\Models\User;

class MessageService
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected UserInterface $userRepository,
		protected MessageInterface $messageRepository
	) {}

	/**
	* print information page with confirm or cancel
	* @param string $title
	* @param string $text
	* @param string $confirmAction 
	* @param string $hidden
	*
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
	 *
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
	 * get messages sorted by user
	 * @param User $user
	 * @param int $perPage
	 *
	 * @return Collection
	*/
	public function getByUsers(User $user, int $perPage)
	{
		$messages 			= $this->messageRepository->getAll($user->id, $perPage);
		return	$this->messageRepository->getNewsByUsers($messages, $user);
	}

	/**
	 * create a private message and send info about it to the user
	 * @param array $data
	 * @param integer $senderId
	 * @param integer $receiverId
	 *
	 * @return void
	*/
	public function create(array $data, int $senderId, int $receiverId)
	{
		$user = $this->userRepository->getJustById($senderId);
		$receiver = $this->userRepository->getJustById($receiverId);

		if (!$user || !$receiver) {
			abort(404);
		}

		$data['sent_user_id'] = $senderId;
		$data['received_user_id'] = $receiverId;

		$this->messageRepository->store($data);

		if ($receiver->dont_send_email != 1) {
			Mail::mailer(config('mail.mail_mode'))
				->to($receiver->email)
				->send(new NewPrivMessageEmail($receiver));
		}
	}
}