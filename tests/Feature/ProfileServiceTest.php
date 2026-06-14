<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ProfileService;
use App\Services\AnkService;
use App\Interfaces\UserInterface;
use App\Interfaces\AnketVisitInterface;
use App\Interfaces\AnketEvaluationInterface;
use App\Models\User;
use Tests\Traits\hasSetupPrepare;
use App\DTO\UpdateMainProfileDTO;
use App\DTO\UpdateSecondProfileDTO;
use App\DTO\UpdatePartnerProfileDTO;
use Mockery;

class ProfileServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected UserInterface $repository;
	protected AnkService $ankService;
	protected AnketVisitInterface $anketVisitRepository;
	protected AnketEvaluationInterface $anketEvaluationRepository;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->repository = Mockery::mock(UserInterface::class);
		$this->ankService = Mockery::mock(AnkService::class);
		$this->anketVisitRepository = Mockery::mock(AnketVisitInterface::class);
		$this->anketEvaluationRepository = Mockery::mock(AnketEvaluationInterface::class);
	}

	public function test_update_main_calls_repository(): void
	{

		$service = new ProfileService($this->repository, $this->ankService, $this->anketVisitRepository, $this->anketEvaluationRepository);

		$user = User::factory()->make();

		$dto = Mockery::mock(UpdateMainProfileDTO::class);
		$dto->shouldReceive('toArray')->once()->andReturn(['name' => 'John']);

		$this->repository->shouldReceive('update')
			->once()
			->with($user, ['name' => 'John']);

		$service->updateMain($user, $dto);
	}

	public function test_update_second_calls_repository(): void
	{
		$this->repository = Mockery::mock(UserInterface::class);
		$service = new ProfileService($this->repository, $this->ankService, $this->anketVisitRepository, $this->anketEvaluationRepository);

		$user = User::factory()->make();

		$dto = Mockery::mock(UpdateSecondProfileDTO::class);
		$dto->shouldReceive('toArray')->once()->andReturn(['age' => 25]);

		$this->repository->shouldReceive('update')
			->once()
			->with($user, ['age' => 25]);

		$service->updateSecond($user, $dto);
	}

	public function test_update_partner_calls_repository(): void
	{
		$this->repository = Mockery::mock(UserInterface::class);
		$service = new ProfileService($this->repository, $this->ankService, $this->anketVisitRepository, $this->anketEvaluationRepository);

		$user = User::factory()->make();

		$dto = Mockery::mock(UpdatePartnerProfileDTO::class);
		$dto->shouldReceive('toArray')->once()->andReturn(['partner_age' => 30]);

		$this->repository->shouldReceive('update')
			->once()
			->with($user, ['partner_age' => 30]);

		$service->updatePartner($user, $dto);
	}
}
