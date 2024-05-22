<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfileBrief extends Component
{
	public $item, $best;
	/**
	 * Create a new component instance.
	 */
	public function __construct($item, $best = 0)
	{
		$this->item = $item;
		$this->best = (int)$best;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
    	return view('components.ankets.brief');
	}
}
