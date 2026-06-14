<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Top100Service;
use App\Interfaces\UserInterface;
use App\Models\User;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class Top100ServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_update_returns_conditions_if_no_photos(): void
	{
		$repository = Mockery::mock(UserInterface::class);

		$user = new User();
		$user->setRelation('photo', collect([]));

		$repository->shouldNotReceive('update');

		$service = new Top100Service($repository);

		$result = $service->update($user);

		$this->assertEquals(['conditions' => true], $result);
	}

    public function test_update_updates_user_if_has_photos(): void
    {
        $repository = Mockery::mock(UserInterface::class);

        $user = new User();
        $user->setRelation('photo', collect([(object)['id' => 1]]));

        $repository->shouldReceive('update')
            ->once()
            ->withArgs(function ($u, $data) use ($user) {
                return $u === $user
                    && isset($data['top100']);
            });

        $service = new Top100Service($repository);

        $result = $service->update($user);

        $this->assertEquals(['success' => 'Информация сохранена.'], $result);
    }
}