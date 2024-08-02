<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Repositories\GoroskopRepository;
use App\Interfaces\GoroskopInterface;

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