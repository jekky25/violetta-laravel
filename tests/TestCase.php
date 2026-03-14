<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	protected static bool $migrated = false;

	protected function setUp(): void
	{
		parent::setUp();
		if (!self::$migrated) {
			$this->artisan('migrate:fresh');
			self::$migrated = true;
		}
	}

	protected function beforeApplicationDestroyed(callable $callback)
    {
		$callback = function () {
			RefreshDatabaseState::$migrated = false;
        };

		$this->beforeApplicationDestroyedCallbacks[] = $callback;
    }
}
