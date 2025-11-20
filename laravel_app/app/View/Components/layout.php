<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class layout extends Component
{
    public $title;
    public $lang;

    /**
     * Create a new component instance.
     */
    public function __construct($title = 'Laravel', $lang = null)
    {
        $this->title = $title;
        // ブラウザの言語設定を取得 (例: 'ja', 'en-US' など)
        $this->lang = $lang ?? request()->getPreferredLanguage();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout');
    }
}
