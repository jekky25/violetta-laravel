<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LiterMenu extends Component
{
	public $alphabet, $sex;

	/**
	 * Create a new component instance.
	*/
	public function __construct(array $alphabet, string $sex)
	{
		$this->alphabet	  = $alphabet;
		$this->sex        = $sex;
	}

	/**
	 * Get the view / contents that represent the component.
	*/
	public function render(): View|Closure|string
	{
		return view('components.liter_menu');
	}
}