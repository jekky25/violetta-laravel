<?php

namespace App\includes\Horoscope\Interfaces;

interface Horoscope {
	public function getText() :string;
	public function getTitle() :string;
}