<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Repositories\UserRepository;

class StatisticsTest extends TestCase
{
	use hasSetupPrepare;

	protected $userRepository;

	/**
	 * Set up variables
	 */
	protected function setUp(): void
	{
		$this->userRepository		= new UserRepository;
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_get_block_statistics(): void
	{
		$statProfiles	= $this->userRepository->getStatistic();
		$check = [
			"total_women",
			"total_men",
			"total_fotos",
			"women_ank_percent",
			"total_women_percent",
			"men_ank_percent",
			"total_men_percent"
		];
		foreach ($check as $_field) {
			$this->assertArrayHasKey($_field, $statProfiles);
		}
	}
}
