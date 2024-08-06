<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Repositories\GoroskopRepository;
use App\Repositories\NameRepository;
use App\Repositories\GoroskopTypeRepository;
use App\Repositories\ScreenRepository;
use App\Repositories\AnketEvaluationRepository;
use App\Repositories\AnketVisitRepository;
use App\Repositories\BanListRepository;
use App\Repositories\UserPropertyRepository;
use App\Repositories\CityRepository;

use App\Interfaces\GoroskopInterface;
use App\Interfaces\NameInterface;
use App\Interfaces\GoroskopTypeInterface;
use App\Interfaces\ScreenInterface;
use App\Interfaces\AnketEvaluationInterface;
use App\Interfaces\AnketVisitInterface;
use App\Interfaces\BanListInterface;
use App\Interfaces\UserPropertyInterface;
use App\Interfaces\CityInterface;

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
		$this->app->bind(AnketEvaluationInterface::class, AnketEvaluationRepository::class);
		$this->app->bind(AnketVisitInterface::class, AnketVisitRepository::class);
		$this->app->bind(BanListInterface::class, BanListRepository::class);
		$this->app->bind(UserPropertyInterface::class, UserPropertyRepository::class);
		$this->app->bind(CityInterface::class, CityRepository::class);
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