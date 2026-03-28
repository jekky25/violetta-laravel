<?php

namespace App\Enums;

enum HoroscopeType: int
{
	const DEFAULT = 1;
	const MAX = 5;

	/**
	* map type
	*/
	public static function toView($type): int
	{
		return $type > 0 && $type <= self::MAX ? $type : self::DEFAULT;
	}
}
