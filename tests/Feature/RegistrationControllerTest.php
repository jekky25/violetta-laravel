<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;

class RegistrationControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
		config(['services.recaptcha.enabled' => false]);
	}

	public function test_store_calls_service_logs_in_and_redirects(): void
	{
		$data = [
			"login" => "ewrerwrwrwe",
			"password" => "222222",
			"password_second" => "222222",
			"name" => "ewrrwerewrew",
			"birth_day" => "04",
			"birth_month" => "05",
			"birth_year" => "1914",
			"sex" => 1,
			"email" => "ewrewrr@dsfg.ru",
			"country_id" => 45,
			"region_id" => 305,
			"city_id" => 1139,
			"conditions" => "on",
			'recaptcha_response' => 'fgdgdfgdfgdfgdgdfg'
		];

		$_SERVER["REMOTE_ADDR"] = '127.0.0.1';
		$_SERVER['REQUEST_URI'] = route('registration.post');

		$response = $this->post($_SERVER['REQUEST_URI'], $data);

		$response->assertRedirect();
		$response->assertSessionHas('success');

		$this->assertAuthenticated();
	}
}
