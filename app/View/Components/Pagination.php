<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
    public $items, $pagination;

    /**
     * Create a new component instance.
     */
    public function __construct($items, $pagination)
    {
        $this->items        = $items;
		$this->pagination   = $pagination;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pagination');
    }
}
