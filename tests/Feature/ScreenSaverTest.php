<?php

namespace Tests\Feature;
use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Screen;

class ScreenSaverTest extends TestCase
{
	use DatabaseMigrations, hasSetupPrepare;

	protected $screenSavers		= null;
	protected $screenSaversCount	= 0;

	/**
	 * Set up variables
	 */
	protected function setUp() :void
	{
		parent::setUp();
		self::setUpPrepare();
		$this->screenSavers = Screen::factory(10)->create();
		$this->screenSaversCount = $this->screenSavers->count();
	}

	/**
	 * Get random id of the dreambook
	 * @return int
	 */
	protected function getRand()
	{
		return rand(0, $this->screenSaversCount);
	}

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
			'screensaver/' . $this->getRand() . '.html',
			'screensaver/' . $this->getRand() . '.html'
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
