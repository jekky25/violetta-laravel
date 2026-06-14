<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ForgetPasswordService;
use App\Interfaces\UserInterface;
use App\DTO\ForgetPasswordDTO;
use App\Models\User;
use App\Mail\ForgetPasswordEmail;
use Illuminate\Support\Facades\Mail;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class ForgetPasswordServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

    public function test_send_password_sends_email_if_user_exists(): void
    {
        Mail::fake();

        $repository = Mockery::mock(UserInterface::class);
        $service = new ForgetPasswordService($repository);

        $user = User::factory()->make([
            'email' => 'test@mail.com'
        ]);

        $repository->shouldReceive('getByEmail')
            ->once()
            ->with('test@mail.com')
            ->andReturn($user);

		$dto = new ForgetPasswordDTO('test@mail.com');
        $service->sendPassword($dto);

        Mail::assertSent(ForgetPasswordEmail::class, function ($mail) {
            return $mail->hasTo('test@mail.com');
        });
    }

    public function test_send_password_does_nothing_if_user_not_found(): void
    {
        Mail::fake();

        $repository = Mockery::mock(UserInterface::class);
        $service = new ForgetPasswordService($repository);

        $repository->shouldReceive('getByEmail')
            ->once()
            ->andReturn(null);

		$dto = new ForgetPasswordDTO('notfound@mail.com');
        $service->sendPassword($dto);

        Mail::assertNothingSent();
    }
}