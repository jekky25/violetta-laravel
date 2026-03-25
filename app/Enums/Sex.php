<?php

namespace App\Enums;

enum Sex: string
{
	case MALE = 'm';
	case FEMALE = 'f';

	/**
	* map sex for DB request
	*/
	public function toView(): string
	{
		return $this === self::MALE ? 'men' : 'women';
	}

	/**
	* map sex for view
	*/
	public static function fromView(string $value): self
	{
		return $value === 'women'
			? self::FEMALE
			: self::MALE;
	}
}
