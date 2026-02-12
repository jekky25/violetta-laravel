<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
	public $name, $type, $class, $value;

	private $accessTypes = [
		'text',
		'password'
	];

	/**
	 * Create a new component instance.
	 */
	public function __construct($name, $type = '', $class = '', $value = '')
	{
		if (!$this->checkTypes($type)) throw new \Exception('Not valid input type');

		$this->name     = $name;
		$this->type     = $type;
		$this->value    = $value;
		$this->class    = $class;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.blocks.input');
	}

	/**
	 * Check types validation
	 * @param string|null $type
	 * @return bool
	 */
	private function checkTypes($type) {
		if ($type === '') return true;
		if (!in_array($type, $this->accessTypes)) return false;
		return true;
	}
}
