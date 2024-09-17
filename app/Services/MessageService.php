<?
namespace App\Services;

class MessageService
{
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
		return response()->view ('mess_die.confirm',
		[
			'msgTitle' 		=> $title,
			'msgText'		=> $text,
			'confirmAction' => $confirmAction,
			'hidden'		=> $hidden
		])->send();
	}

}