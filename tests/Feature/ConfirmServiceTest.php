<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ConfirmService;
use App\Interfaces\UserInterface;
use App\Models\User;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class ConfirmServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

    public function test_update_aborts_if_code_empty(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $repository = Mockery::mock(UserInterface::class);
        $service = new ConfirmService($repository);

        $service->confirmEmail(1, '');
    }

    public function test_update_returns_false_if_user_not_found(): void
    {
        $repository = Mockery::mock(UserInterface::class);
        $repository->shouldReceive('getByIdAndConfirmCode')
            ->once()
            ->with(1, 'code')
            ->andReturn(null);

        $service = new ConfirmService($repository);

        $result = $service->confirmEmail(1, 'code');

        $this->assertFalse($result);
    }

    public function test_update_updates_user_and_returns_true(): void
    {
        $repository = Mockery::mock(UserInterface::class);

        $user = User::factory()->make();

        $repository->shouldReceive('getByIdAndConfirmCode')
            ->once()
            ->with(1, 'code')
            ->andReturn($user);

        $repository->shouldReceive('update')
            ->once()
            ->with($user, Mockery::on(function ($data) {
                return $data['confirm_email'] === 1
                    && $data['submit_code'] === '';
            }));

        $service = new ConfirmService($repository);

        $result = $service->confirmEmail(1, 'code');

        $this->assertTrue($result);
    }
}