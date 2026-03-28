<?php

namespace App\Enums;

enum HoroscopeType: int
{
	const DEFAULT_TYPE = 1;
	const MAX_TYPE = 5;

	/**
	* map type
	*/
	public static function toView($type): int
	{
		return $type > 0 && $type <= self::MAX_TYPE ? $type : self::DEFAULT_TYPE;
	}
}
