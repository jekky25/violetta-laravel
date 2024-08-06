<?php

namespace Tests\Feature;
use Tests\TestCase;

class ProfileTest extends TestCase
{
	/**
	* Test a profile id page
	*/
	public function test_profile_id_page(): void
	{
		$ar = [
			'/ank/1/',
			'/ank/3/',
			'/ank/4/',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	* Test a full profile id page
	*/
	public function test_full_profile_id_page(): void
	{
		$ar = [
			'/ank/f/1/',
			'/ank/f/3/',
			'/ank/f/4/',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}


	/**
	* Test a picture profile page
	*/
	/*
	public function test_picture_profile_id_page(): void
	{
		$ar = [
			'ank/photo/1.html',
		];
		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
	*/
}
