<?php

namespace Bigmom\VeEditor\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('ve-editor.layouts.app');
    }
}
