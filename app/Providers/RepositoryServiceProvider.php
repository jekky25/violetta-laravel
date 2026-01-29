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
use App\Repositories\RegionRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CommentScreenRepository;
use App\Repositories\DiaryRepository;
use App\Repositories\DiaryCommentRepository;
use App\Repositories\DreamBookRepository;
use App\Repositories\MessageRepository;
use App\Repositories\PhotoRepository;
use App\Repositories\SmileRepository;
use App\Repositories\VarsRepository;
use App\Repositories\UserRepository;
use App\Repositories\CommentPhotoRepository;
use App\Repositories\ForumRepository;

use App\Interfaces\GoroskopInterface;
use App\Interfaces\NameInterface;
use App\Interfaces\GoroskopTypeInterface;
use App\Interfaces\ScreenInterface;
use App\Interfaces\AnketEvaluationInterface;
use App\Interfaces\AnketVisitInterface;
use App\Interfaces\BanListInterface;
use App\Interfaces\UserPropertyInterface;
use App\Interfaces\CityInterface;
use App\Interfaces\RegionInterface;
use App\Interfaces\CountryInterface;
use App\Interfaces\CommentScreenInterface;
use App\Interfaces\DiaryInterface;
use App\Interfaces\DiaryCommentInterface;
use App\Interfaces\DreamBookInterface;
use App\Interfaces\MessageInterface;
use App\Interfaces\PhotoInterface;
use App\Interfaces\SmileInterface;
use App\Interfaces\VarsInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\CommentPhotoInterface;
use App\Interfaces\ForumInterface;

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
		$this->app->bind(RegionInterface::class, RegionRepository::class);
		$this->app->bind(CountryInterface::class, CountryRepository::class);
		$this->app->bind(CommentScreenInterface::class, CommentScreenRepository::class);
		$this->app->bind(DiaryInterface::class, DiaryRepository::class);
		$this->app->bind(DiaryCommentInterface::class, DiaryCommentRepository::class);
		$this->app->bind(DreamBookInterface::class, DreamBookRepository::class);
		$this->app->bind(MessageInterface::class, MessageRepository::class);
		$this->app->bind(PhotoInterface::class, PhotoRepository::class);
		$this->app->bind(SmileInterface::class, SmileRepository::class);
		$this->app->bind(VarsInterface::class, VarsRepository::class);
		$this->app->bind(UserInterface::class, UserRepository::class);
		$this->app->bind(CommentPhotoInterface::class, CommentPhotoRepository::class);
		$this->app->bind(ForumInterface::class, ForumRepository::class);
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {}
}
