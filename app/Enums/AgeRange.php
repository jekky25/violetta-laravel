<?php

namespace App\Enums;

enum AgeRange: string
{
	const MEN = 'men';
	const WOMEN = 'women';
	const AGE_MAP = [
    '20' => [
        'min' => null,
        'max' => 20,
        'title' => 'до 20 лет',
    ],
    '2025' => [
        'min' => 20,
        'max' => 25,
        'title' => '20 - 25 лет',
    ],
    '2535' => [
        'min' => 25,
        'max' => 35,
        'title' => '25 - 35 лет',
    ],
    '3550' => [
        'min' => 35,
        'max' => 50,
        'title' => '35 - 50 лет',
    ],
    '50' => [
        'min' => 50,
        'max' => null,
        'title' => 'от 50 лет',
    ],
	];

	public static function title(?string $sex, ?string $age): string 
	{ 
		$str = 'Анкеты';
		if($sex === null) return $str;
		$sexStr = $sex == self::MEN ? 'мужчины' : 'женщины';
		$str .= ': ' . $sexStr;
		if ($age === null) return $str;

		$opStr = self::AGE_MAP[$age]['title'];
		return $str . ', ' . $opStr;
	}

	public static function titleForMeta(?string $sex, ?string $age): string 
	{
		if($sex === null && $age === null) return 'Поиск анкет';
		return str_replace(
        ['Анкеты:', 'мужчины', 'женщины'],
        ['Анкеты', 'мужчин', 'женщин'],
        self::title($sex, $age));
	}
}
