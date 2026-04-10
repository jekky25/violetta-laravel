<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ProfileService;
use App\Interfaces\UserInterface;
use App\Models\User;
use Tests\Traits\hasSetupPrepare;
use App\DTO\UpdateMainProfileDTO;
use App\DTO\UpdateSecondProfileDTO;
use App\DTO\UpdatePartnerProfileDTO;
use Mockery;

class ProfileServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

    public function test_update_main_calls_repository(): void
    {
        $repository = Mockery::mock(UserInterface::class);
        $service = new ProfileService($repository);

        $user = User::factory()->make();

        $dto = Mockery::mock(UpdateMainProfileDTO::class);
        $dto->shouldReceive('toArray')->once()->andReturn(['name' => 'John']);

        $repository->shouldReceive('update')
            ->once()
            ->with($user, ['name' => 'John']);

        $service->updateMain($user, $dto);
    }

    public function test_update_second_calls_repository(): void
    {
        $repository = Mockery::mock(UserInterface::class);
        $service = new ProfileService($repository);

        $user = User::factory()->make();

        $dto = Mockery::mock(UpdateSecondProfileDTO::class);
        $dto->shouldReceive('toArray')->once()->andReturn(['age' => 25]);

        $repository->shouldReceive('update')
            ->once()
            ->with($user, ['age' => 25]);

        $service->updateSecond($user, $dto);
    }

    public function test_update_partner_calls_repository(): void
    {
        $repository = Mockery::mock(UserInterface::class);
        $service = new ProfileService($repository);

        $user = User::factory()->make();

        $dto = Mockery::mock(UpdatePartnerProfileDTO::class);
        $dto->shouldReceive('toArray')->once()->andReturn(['partner_age' => 30]);

        $repository->shouldReceive('update')
            ->once()
            ->with($user, ['partner_age' => 30]);

        $service->updatePartner($user, $dto);
    }
}