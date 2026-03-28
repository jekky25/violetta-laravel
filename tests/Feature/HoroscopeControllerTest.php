<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\HoroscopeService;
use App\DTO\HoroscopeIndexPageDTO;
use Mockery;

class HoroscopeControllerTest extends TestCase
{
	protected $service;

	protected function setUp(): void
	{
		parent::setUp();

		$this->service = Mockery::mock(HoroscopeService::class);

		$this->app->instance(HoroscopeService::class, $this->service);
	}

	public function test_index_page()
	{
		$dto = new HoroscopeIndexPageDTO(
			horoscopes: collect(),
			zodiak_text: 'text',
			horoscope_title: 'title',
			horoscopes_type: collect()
		);

		$this->service
			->shouldReceive('getIndexData')
			->once()
			->andReturn($dto);

		$_SERVER['REQUEST_URI'] = route('horoscope');
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertStatus(200);
		$response->assertViewIs('horoscope');
		$response->assertViewHas('data');
	}

	public function test_show_item_page()
	{
		$dto = new HoroscopeIndexPageDTO(
			horoscopes: collect(),
			zodiak_text: 'desc',
			horoscope_title: 'Leo',
			horoscopes_type: collect()
		);

		$this->service
			->shouldReceive('getItemData')
			->with(1)
			->andReturn($dto);

		$_SERVER['REQUEST_URI'] = route('horoscope.id', 1);
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertStatus(200);
		$response->assertViewHas('data');
	}

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}
}
