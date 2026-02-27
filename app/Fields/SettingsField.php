<?php
namespace App\Fields;

use App\Interfaces\CountryInterface;
use App\Services\FormatService;
use App\Services\DataService;

class SettingsField extends Field
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected CountryInterface $country,
		protected DataService $data,
		protected FormatService $format,
	)
	{
		self::$user = $this->user();
		parent::__construct($country, $data, $format);
	}

	public function settings($selected = null) :\Illuminate\Support\Collection
	{
		$ar = ['id' => 1, 'name' => 'Не получать сообщения с сайта'];
		if ($selected == true) $ar['selected'] = true;
		return collect([(object)$ar]);
	}
}