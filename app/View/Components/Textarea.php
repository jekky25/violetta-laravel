<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
	public $name, $class, $value;

	/**
	 * Create a new component instance.
	 */
	public function __construct($name, $class = '', $value = '')
	{
		$this->name     = $name;
		$this->value    = $value;
		$this->class    = $class;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.blocks.textarea');
	}
}
