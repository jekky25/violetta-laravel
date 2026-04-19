<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\RegistrationService;
use App\Interfaces\UserInterface;
use App\DTO\RegistrationProfileDTO;
use App\Models\User;
use App\Mail\RegistrationEmail;
use Illuminate\Support\Facades\Mail;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class RegistrationServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

    public function test_store_creates_user_sends_email_and_returns_user(): void
    {
        Mail::fake();

        $repository = Mockery::mock(UserInterface::class);
        $service = new RegistrationService($repository);

		$data = [
				"login" => "ewrerwrwrwe",
				"password" => "222222",
				"name" => "ewrrwerewrew",
				"sex" => 1,
				"email" => "ewrewrr@dsfg.ru",
				"country_id" => 45,
				"region_id" => 305,
				"city_id" => 1139,
				"conditions" => "on",
  				"birth_date" => "1914-05-04"];

        $dto = Mockery::mock(RegistrationProfileDTO::class);

        $dto->shouldReceive('toArray')
            ->once()
            ->andReturn($data);

        $user = new User($data);

        $repository->shouldReceive('create')
            ->once()
			->withAnyArgs()
			->andReturn($user);

        $result = $service->store($dto);

		$this->assertEquals($user, $result);

        Mail::assertSent(RegistrationEmail::class, function ($mail) use($data) {
            return $mail->hasTo($data['email']);
        });
    }
}