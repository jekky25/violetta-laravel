<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Goroskop;
use App\Models\GoroskopType;

class GoroskopController extends Controller
{

	public 	$typeGor 	= 1;
	public  $google		= 
	[
		[
			'text' => '468 на 60 добавлен в гороскопы',
			'slot' => '4331350870',
			'width' => 468,
			'height' => 60
		],
		[
			'text' => '234x60 на гороскопах',
			'slot' => '7650642805',
			'width' => 234,
			'height' => 60
		]
	];
	

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
		$goroskops 		= Goroskop::getByType($this->typeGor);
		$goroskopsType 	= GoroskopType::getNotByType($this->typeGor);

		if (empty ($goroskops)) abort(404);

		include('../app/includes/goroskop/zodiak_text.php');

		$goroskopsTitle  = 'Гороскопы - Зодиак';

		return response()->view ('goroskop', 
		[
			'goroskops'			=> $goroskops,
			'zodiak_text' 		=> $zodiakText,
			'goroskopsTitle' 	=> $goroskopsTitle,
			'goroskops_type' 	=> $goroskopsType,
			
		]);
    }

	/**
     * show goroskop by id
     *
     * @return \Illuminate\Http\Response
     */
	public function getItem(Request $request, $id)
	{
		$goroskop = Goroskop::getById($id);
		if (empty ($goroskop)) abort(404);
		
		$this->typeGor 	= $goroskop->gor_type;
		$goroskops 		= Goroskop::getByType($this->typeGor);
		$goroskopsType 	= GoroskopType::getNotByType($this->typeGor);
		
		$goroskop->gor_text = str_replace("\n","<br \><br \>\n",$goroskop->gor_text);
	
		if (!empty ($this->google))
		{	
			$k = 0;
			foreach ($this->google as $_google)
			{
				$k++;
				$countStr = 0;
				$countStr = strpos ($goroskop->gor_text, '{google_baner' . $k . '}');
				$googleBan = '</p>
				<p class="gb1">
				<script type="text/javascript"><!--
					google_ad_client = "ca-pub-6379140164632940";
					/* ' . $_google['text'] . ' */
					google_ad_slot = "' . $_google['slot'] . '";
					google_ad_width = ' . $_google['width'] . ';
					google_ad_height = ' . $_google['height'] . ';
				//-->
				</script>
				<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
				</p>
				<p>';
		
				if ($countStr > 0) {
					$goroskop->gor_text = substr_replace($goroskop->gor_text, $googleBan, $countStr, 15);
				}
			}
		}

		$zodiakText 	= $goroskop->gor_text;
		$goroskopsTitle = $goroskop->gor_name;
	
		
		return response()->view ('goroskop', 
		[
			'goroskops'			=> $goroskops,
			'goroskop'			=> $goroskop,
			'zodiak_text' 		=> $zodiakText,
			'goroskopsTitle' 	=> $goroskopsTitle,
			'goroskops_type' 	=> $goroskopsType
		]);

	}

	/**
     * show goroskop by id
     *
     * @return \Illuminate\Http\Response
     */
	public function getType(Request $request, $id = 0)
	{
		$id = (int) $id;
		if ($id == 0 && $id > 5) abort(404);
		$goroskopsType = $id;
		switch ($id) {
			case 2:
				include('../app/includes/goroskop/vost_goroskop_text.php');
				$goroskopsTitle = 'Гороскопы - Восточный гороскоп';
				$title_id = 'Восточный гороскоп';
				break;
			case 2:
				include('../app/includes/goroskop/gall_goroskop_text.php');
				$goroskopsTitle = 'Гороскопы - Галлийский гороскоп';
				$title_id = 'Галлийский гороскоп';
				break;
			case 4:
				include('../app/includes/goroskop/cvet_goroskop_text.php');
				$goroskopsTitle = 'Гороскопы - Гороскоп цветов';
				$title_id = 'Гороскоп цветов';
				break;
			case 5:
				include('../app/includes/goroskop/talisman_text.php');
				$goroskopsTitle = 'Гороскопы - Талисманы';
				$title_id = 'Талисманы';
				break;
			default:		
				include('../app/includes/goroskop/zodiak_text.php');
				$goroskopsTitle = 'Гороскопы - Зодиак';
				$title_id = 'Зодиак';
		}

		$goroskops 		= Goroskop::getByType($goroskopsType);
		$goroskopsType 	= GoroskopType::getNotByType($goroskopsType);

		return response()->view ('goroskop', 
		[
			'goroskops'			=> $goroskops,
			'zodiak_text' 		=> $zodiakText,
			'goroskopsTitle' 	=> $goroskopsTitle,
			'goroskops_type' 	=> $goroskopsType
		]);

	}
}

