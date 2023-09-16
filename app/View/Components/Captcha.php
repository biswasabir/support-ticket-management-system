<?php

namespace App\View\Components;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\View\Component;

class Captcha extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (extension('google_recaptcha')->status) {
            $scripts = NoCaptcha::renderJs(app()->getLocale());
            return view('components.captcha', ['scripts' => $scripts]);
        }
    }
}
