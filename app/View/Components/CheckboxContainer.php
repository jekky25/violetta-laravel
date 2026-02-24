<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CheckboxContainer extends Component
{
	public $name, $obj, $containerTitle, $clearName, $colums, $colsClass, $topClass;

	/**
	 * Create a new component instance.
	 */
	public function __construct($name, $obj, $title = '', $colums = 1, $topClass = '')
	{
		$this->name             	= $name;
		$this->clearName			= $this->clear($name);
		$this->obj              	= $obj;
		$this->containerTitle   	= $title;
		$this->colums           	= $colums;
		$this->colsClass			= $colums > 1 ? 'col-' . $colums : null;
		$this->topClass	= $topClass;
	}

	/**
	 * this method clears a field name from any spetial symbols if it has
	 * @param string $name
	 * @return string
	 */
	private function clear($name)
	{
		return str_replace('[]', '', $name);
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.blocks.checkbox_container');
	}
}
