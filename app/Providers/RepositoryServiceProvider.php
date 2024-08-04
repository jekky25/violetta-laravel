<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Repositories\GoroskopRepository;
use App\Repositories\NameRepository;
use App\Repositories\GoroskopTypeRepository;
use App\Repositories\ScreenRepository;
use App\Interfaces\GoroskopInterface;
use App\Interfaces\NameInterface;
use App\Interfaces\GoroskopTypeInterface;
use App\Interfaces\ScreenInterface;

class RepositoryServiceProvider extends ServiceProvider
{
	/**
	* Register services.
	*
	* @return void
	*/
	public function register()
	{
		$this->app->bind(GoroskopInterface::class, GoroskopRepository::class);
		$this->app->bind(GoroskopTypeInterface::class, GoroskopTypeRepository::class);
		$this->app->bind(NameInterface::class, NameRepository::class);
		$this->app->bind(ScreenInterface::class, ScreenRepository::class);
	}

	/**
	* Bootstrap services.
	*
	* @return void
	*/
	public function boot()
	{
	}
}