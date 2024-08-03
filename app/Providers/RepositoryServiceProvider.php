<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Repositories\GoroskopRepository;
use App\Interfaces\GoroskopInterface;
use App\Repositories\GoroskopTypeRepository;
use App\Interfaces\GoroskopTypeInterface;

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