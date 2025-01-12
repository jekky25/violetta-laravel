<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Repositories\UserRepository;

class Top100 extends Component
{
	public $sex;
	public $top100;
	public UserRepository $userRepository;
	private $count = 1;

	/**
	 * Create a new component instance.
	 */
	public function __construct(UserRepository $userRepository, $sex)
	{
		$this->userRepository 		= $userRepository;
		$this->sex = $sex;
		$this->top100 = $this->getTop100($sex);
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.blocks.top100');
	}

	/**
	 * Get Top100 profile
	 * @param  int $sex
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	private function getTop100($sex)
	{
		return $this->userRepository->getTop100($sex, $this->count);
	}
}
