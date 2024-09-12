<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\NameInterface;
use App\Services\NameService;

class NameController extends Controller
{
	const ALPHABET = [
		1 => 'A',
		2 => 'Б',
		3 => 'В',
		4 => 'Г',
		5 => 'Д',
		6 => 'Е',
		7 => 'Ж',
		8 => 'З',
		9 => 'И',
		10 => 'К',
		11 => 'Л',
		12 => 'М',
		13 => 'Н',
		14 => 'О',
		15 => 'П',
		16 => 'Р',
		17 => 'С',
		18 => 'Т',
		19 => 'Ф',
		20 => 'Х',
		22 => 'Ц',
		21 => 'Э',
		23 => 'Ю',
		24 => 'Я'];

	protected $nameService;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected NameInterface $nameRepository,
	)
	{
		$this->nameService = new NameService(self::ALPHABET);
	}

	/**
	* show the names page
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		for ($i = 1; $i <= count (self::ALPHABET); $i++)
		{
			$names ['m'][$i] = $this->nameRepository->getPart($i,'m');
			$names ['w'][$i] = $this->nameRepository->getPart($i,'f');
		}
		return response()->view ('names', 
		[
			'alphabet'			=> self::ALPHABET,
			'names'				=> $names
		]);
    }

	/**
	* Show genderType page
	* @param string $sex
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function getGender($sex, $id = 1)
	{
		$nameTitle 		= $sex == 'men' ? 'Значение мужского имени' : 'Значение женского имени';
		$s 				= $sex == 'men' ? 'm' 						: 'f';
		$names 			= $this->nameRepository->getAllbySex($s, $id);
		$namesGender	= $this->nameService->getLiteralString($sex);
		return response()->view ('names.gender', 
		[
			'sex'				=> $sex,
			'alphabet'			=> self::ALPHABET,
			'names'				=> $names,
			'nameTitle'			=> $nameTitle,
			'namesGender'		=> $namesGender
		]);
	}

	/**
	* Show the name page by id
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function getName($id)
	{
		$name = $this->nameRepository->getById($id);
		$nameTitle			= 'Значение имени ' . $name->name;
		$nameText			= str_replace("\n","<br /><br />\n",$name->description);
		$sex				= $name->gender == 'm' ? 'men' 							: 'women';
		$nameTitleGender	= $name->gender == 'm' ? 'Мужские имена по алфавиту' 	: 'Женские имена по алфавиту';
		$namesGender		= $this->nameService->getLiteralString($sex);
		$bannerNames		= $this->nameService->addLink($id);

		return response()->view ('names.id', 
		[
			'name'				=> $name,
			'nameTitle'			=> $nameTitle,
			'alphabet'			=> self::ALPHABET,
			'nameText'			=> $nameText,
			'namesGender'		=> $namesGender,
			'nameTitleGender'	=> $nameTitleGender,
			'bannerNames'		=> $bannerNames
		]);
	}
}