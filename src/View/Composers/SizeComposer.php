<?php

namespace Bigmom\VeEditor\View\Composers;

use Bigmom\VeEditor\Facades\Asset;
use Illuminate\View\View;

class SizeComposer
{
    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $occupiedSize = $this->parseSize(Asset::getOccupiedSize());
        $view->with('occupiedSize', $occupiedSize);
        $maxSize = config('ve.size-limit');
        if (config('ve.size-limit')){
            $maxSize = $this->parseSize(config('ve.size-limit'));
            $view->with('maxSize', $maxSize);
        }
    }

    private function parseSize($size)
    {
        $size = (double) $size;
        $unit = 'B';
        if ($size / 1000000000 >= 1) {
            $size = number_format($size / 1000000000, 2);
            $unit = 'GB';
        } else if ($size / 1000000 >= 1) {
            $size = number_format($size / 1000000, 2);
            $unit = 'MB';
        } else if ($size / 1000 >= 1) {
            $size = number_format($size / 1000, 2);
            $unit = 'kB';
        }

        return "$size $unit";
    }
}