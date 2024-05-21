<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Screen;
use App\Models\CommentScreen;
use App\Helpers\Helper;
use Validator;

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
     * @param  \Illuminate\Http\Request  $request
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

	/**
	 * show a screensaver page and make download screensaver
	 * @param  \Illuminate\Http\Request  $request
     * @param int $id
	 * @return \Illuminate\Http\Response
	 */
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
								->withErrors($strError, 'download')
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
				

			} elseif ($request->has('send'))
			{
				$arParams = $request->post();
				$rules = [
					'description'	=> ['required', 'max:1000']
				];
				$errMessages = [
						'description.required' 	=> 'Комментарий не заполнен',
						'description.max'	 	=> 'Ваш комментарий слишком длинный',
				];

				$validator = Validator::make($arParams, $rules, $errMessages);

				if ($validator->fails()) {
					$messages = $validator->messages();
					$strError = $messages;


					return redirect()->back()
								->withErrors($strError, 'comment')
								->withInput();

				}

				$description =  str_replace("\'", "''", $arParams['description']);
        
				$user = Auth::user();
				if (empty ($user))
				{
					return redirect()->route('login');
				}
				$user = $user->load(['visits']);

				$aFields = [
					'scr_id'		=> $screen->id,
					'name'			=> $user->user_name,
					'email' 		=> $user->user_mail,
					'description'	=> $description,
					'time'			=> time()
				];
		
		
				$oComment = new CommentScreen ($aFields);
				$oComment->save();

				return redirect()->back()
								->with('success','Сообщение успешно отправлено')
								->withInput();

			}

		}


		$screen->size_scr 	= Helper::formatFileSize($screen->size_scr);
		$screen->size_rar 	= Helper::formatFileSize($screen->size_rar);

		$comments 			= CommentScreen::getByScrId($id);

		return response()->view ('screensavers_id', 
		[
			'screen'			=> $screen,
			'comments'			=> $comments
		]);

	}
}

