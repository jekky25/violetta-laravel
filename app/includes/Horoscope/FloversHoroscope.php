<?php
namespace App\includes\Horoscope;

use App\includes\Horoscope\Interfaces\Horoscope;

class FloversHoroscope implements Horoscope
{
	/**
	* Get horoscope description
	*/
	public function getText() :string
	{
		return 'С датой рождения человека связаны не только деревья, но и цветы. Представляем гороскоп из 36 видов цветов, которые соответствуют 36 периодам рождения людей и, следовательно, их характерным особенностям, гармонирующим с растительным миром.';
	}

	/**
	* Get a title of horoscope
	*/
	public function getTitle() :string
	{
		return 'Гороскопы - Гороскоп цветов';
	}
}