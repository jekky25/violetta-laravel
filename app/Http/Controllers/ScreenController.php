<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Screen;
use App\Models\CommentScreen;
use App\Helpers\Helper;

class ScreenController extends Controller
{

	public $countPerPage 	= 6;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$screens 			= Screen::get($this->countPerPage);
		$page 				= $screens->currentPage();
		$pagination 		= Helper::preparePagination ($screens->toArray()['links']);
		return response()->view ('screensavers', 
		[
			'page'				=> $page,
			'screens'			=> $screens,
			'pagination'		=> $pagination,
			'numScreens'		=> $screens->total()
		]);
    }

	public function getItem(Request $request, $id)
	{
		$screen 			= Screen::getById($id);
		if (empty ($screen)) abort(404);

		if ($request->isMethod('post'))
		{
			if ($request->has('download'))
			{
				$arParams = $request->post();

				$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
				$recaptcha_secret = RE_SEC_KEY;
				$recaptcha_response = $arParams['recaptcha_response'];

				$ch = curl_init();
				curl_setopt_array($ch, [
					CURLOPT_URL => $recaptcha_url,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => [
					'secret' => $recaptcha_secret,
					'response' => $recaptcha_response,
					'remoteip' => $_SERVER['REMOTE_ADDR']
		   			],
					CURLOPT_RETURNTRANSFER => true
				]);
	
				$output = curl_exec($ch);
				curl_close($ch);
				
				$recaptcha = json_decode($output);

				if ($recaptcha->success === true && $recaptcha->score >= 0.5) {
				} else {
					$strError = 'Капча не пройдена';

					return redirect()->back()
								->withErrors($strError)
								->withInput();

				}

				$screen->zakachka++;
				$screen->save();

				$fDown     = $request->get('f_download') == 2 ? 2 : 1;
				if ($fDown == 1)
				{
					$GetFile 	= "screensavers/" . $screen->path_scr;
					$FileS 		= $screen->name . ".scr";
					$header		= "Content-type: application charset=utf-8";
				} else
				{
					$GetFile 	= "screensavers/" . $screen->path_rar;
					$FileS 		= $screen->name . ".rar";
					$header		= "Content-type: application/x-rar-compressed charset=utf-8";
				}

				header($header);
				header("Content-Disposition: attachment; filename=". $FileS ."");
				header("Content-Length: " . FileSize($GetFile));

				ReadFile($GetFile);
				redirect(route('screensavers.id',$screen->id));
				

			}

		}


		$screen->size_scr 	= Helper::formatFileSize($screen->size_scr);
		$screen->size_rar 	= Helper::formatFileSize($screen->size_rar);

		$comments 			= CommentScreen::getByScrId($id);

		$arParams = $request->post();
		$rules = [
			'name' 		=> ['required', 'string', 'max:30'],
			'email' 	=> ['required', 'email'],
			'subject'	=> ['required', 'max:300'],
			'text'	 	=> ['required', 'max:1000']
		];
		$errMessages = ['name.required' 	=> 'Поле Имя не заполнено',
						'name.max' 			=> 'Поле Имя должно быть не более :max символов',
						'email.required' 	=> 'Поле Емайл не заполнено',
						'email.email' 		=> 'Поле Емайл заполнено не корректно',
						'subject.required' 	=> 'Тема не заполнена',
						'subject.max'	 	=> 'Поле Тема должно быть не более :max символов',
						'text.required' 	=> 'Комментарий не заполнен',
						'text.max'	 	=> 'Ваш комментарий слишком длинный',
		];

		

		return response()->view ('screensavers_id', 
		[
			'screen'			=> $screen,
			'comments'			=> $comments
		]);

	}
}

