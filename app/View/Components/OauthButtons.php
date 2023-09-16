<?php

namespace App\View\Components;

use App\Models\OAuthProvider;
use Illuminate\View\Component;

class OauthButtons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $oauthProviders = OAuthProvider::active()->get();
        return view('components.oauth-buttons', ['oauthProviders' => $oauthProviders]);
    }
}
