<?php

namespace App\Enums;

enum ScreenSaverType: int
{
	const VAR_SCR = 1;
	const VAR_RAR = 2;

	/**
	* map type
	*/
	public static function toView($type): int
	{
		return $type == self::VAR_RAR ? self::VAR_RAR : self::VAR_SCR;
	}
}
