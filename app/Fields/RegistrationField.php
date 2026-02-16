<?php
namespace App\Fields;

class RegistrationField extends Field
{
	public $names = ['day', 'month', 'year', 'country'];

	public function day() :array
	{
		return $this->data->getDays();
	}

	public function month() :array
	{
		return $this->data->getMonths();
	}

	public function year() :array
	{
		return $this->data->getYears();
	}
}