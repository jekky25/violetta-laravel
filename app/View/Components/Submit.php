<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Submit extends Component
{
	public $name, $value, $onclick;

	/**
	 * Create a new component instance.
	 */
	public function __construct($name, $onclick = null, $value = '')
	{
		$this->name     = $name;
		$this->value    = $value;
		$this->onclick  = $onclick;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.blocks.submit');
	}
}
