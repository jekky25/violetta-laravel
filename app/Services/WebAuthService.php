<?

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserInterface;
use Illuminate\Http\RedirectResponse;

class WebAuthService
{
	public function __construct(
		private UserInterface $repository
	) {}

	/**
	 * login user over web
	 */
	public function loginApi(array $data): RedirectResponse
	{
		$user = $this->repository->getByLoginAndPass($data['login'], $data['password']);
		if ($user !== null) {
			Auth::login($user, true);
			return redirect()->route('home');
		} else {
			$errors = [
				'message'	=> 'Неверно указаны имя пользователя или пароль!',
				'title'		=> 'Информация',
				'error'		=> true
			];
			return redirect()->route('home')->with($errors);
		}
	}
}