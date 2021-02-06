<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Loading extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public  $target;
    public function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.loading');
    }
}
