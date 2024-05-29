<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Error extends Component
{
	public $errName, $before, $after;
	/**
	 * Create a new component instance.
	 */
	public function __construct($errName, $before = '', $after = '')
	{
		$this->errName          = $errName;
		$this->before           = $before;
		$this->after            = $after;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.blocks.error');
	}
}
