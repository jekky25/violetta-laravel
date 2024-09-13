<?php

namespace Tests\Feature;
use Tests\TestCase;

class ScreenSaverTest extends TestCase
{
	/**
	* Test a screen saver page
	*/
	public function test_screen_saver_page(): void
	{
		$ar = [
			'screensavers.html',
			'screensavers.html?page=3',
			'screensavers.html?page=4',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	* Test a screen saver id page
	*/
	public function test_screen_saver_id_page(): void
	{
		$ar = [
			'screensaver/43.html',
			'screensaver/44.html'
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	* Test no exist screen saver id page
	*/
	public function test_no_exist_screen_saver_id_page(): void
	{
		$ar = [
			'screensaver/1400.html',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(404);
		}
	}
}
