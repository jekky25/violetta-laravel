<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Services\LengthPager;
use Illuminate\Pagination\LengthAwarePaginator;

class Pagination extends Component
{
	public $items;

	/**
	* Create a new component instance.
	*/
	public function __construct($items)
	{
		$this->items = $items instanceof LengthAwarePaginator 
											?	LengthPager::makeLengthAware($items, $items->total(), $items->perPage())->appends(request()->query()) 
											:	$items;
		$this->pagination   = !empty($this->items) && !empty($this->items->toArray()['links']) ? $this->preparePagination($this->items->toArray()['links']) : [];
	}

	/**
	* Get the view / contents that represent the component.
	*/
	public function render(): View|Closure|string
	{
		return view('components.pagination', 
			[
				'pagination'	=> $this->pagination
			]
		);
	}

	/**
	* preparation pagination to out to the template
	* @param array $paginagion
	*
	* @return array
	*/
	public function preparePagination($pagination)
	{
		if (empty ($pagination)) return [];

		$pagination[0] 		= str_replace (' Previous','', $pagination[0]);
		$pagination[0] 		= str_replace ('&laquo;','&lt;', $pagination[0]);
		$ind 				= count ($pagination) - 1;
		$pagination[$ind] 	= str_replace ('Next ','', $pagination[$ind]);
		$pagination[$ind] 	= str_replace ('&raquo;','&gt;', $pagination[$ind]);

		return $pagination;
	}
}