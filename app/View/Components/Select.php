<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    public $name, $obj, $type, $userProp, $measure, $multiple, $size;
    /**
     * Create a new component instance.
     */
    public function __construct($name, $obj, $type = '', $userProp = '', $measure = '', $multiple = false, $size = 0)
    {
        $this->name     = $name;
        $this->obj      = $obj;
        $this->type     = $type;
        $this->userProp = $userProp;
        $this->measure  = $measure;
        $this->multiple = $multiple;
        $this->size     = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.blocks.select');
    }
}
