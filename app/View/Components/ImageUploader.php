<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImageUploader extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $modal;
    public $item;
    public function __construct($modal, $item)
    {
        $this->modal = $modal;
        $this->item = $item;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.image-uploader');
    }
}
