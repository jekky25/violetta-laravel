<?php

namespace App\Helpers;

final class CacheKeys
{
	public static function screensList($perPage, $page): string
	{
		return "screens.list.{$perPage}.page." . $page;
	}
}
