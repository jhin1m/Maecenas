<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Swiper extends Component
{
    private $link;
    private $title;
    private $items = [];
    /**
     * Create a new component instance.
     */
    public function __construct($link, $title, $items)
    {
        $this->link = $link;
        $this->title = $title;
        $this->items = $items;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.swiper', [
            'link' => $this->link,
            'title' => $this->title,
            'items' => $this->items
        ]);
    }
}
