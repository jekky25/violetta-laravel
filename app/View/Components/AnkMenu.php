<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnkMenu extends Component
{
    public $userData;
    /**
     * Create a new component instance.
     */
    public function __construct($userData)
    {
        $this->userData = $userData;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ankets.menu');
    }
}
