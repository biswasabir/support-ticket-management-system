<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Methods\ReCaptchaValidation;
use App\Models\OAuthProvider;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Validator;

class OAuthController extends Controller
{
    /**
     * Redirect the user to the OAuth provider for authentication.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function redirectToProvider($provider)
    {
        if (demoMode()) {
            toastr()->error(admin_lang('Some features are disabled in the demo version'));
            return back();
        }
        $oauthProvider = OAuthProvider::where('alias', $provider)->firstOrFail();
        return Socialite::driver($oauthProvider->alias)->redirect();
    }

    /**
     * Handles the callback from the OAuth provider and creates or logs in the user.
     *
     * @param Request $request
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        try {

            $oauthProvider = OAuthProvider::where('alias', $provider)->firstOrFail();
            $socialUser = Socialite::driver($oauthProvider->alias)->user();

            $id = $socialUser->getId();
            $name = explode(' ', $socialUser->getName());
            $email = $socialUser->getEmail();

            $userExists = User::where($oauthProvider->alias . '_id', $id)->first();
            if ($userExists) {
                Auth::login($userExists);
                $userExists->updateIpAddress();
                return redirect()->route('user');
            }

            if (!settings('actions')->registration_status) {
                toastr()->error(lang('Registration is currently disabled.', 'auth'));
                return redirect()->route('login');
            }

            if ($email) {
                $emailExists = User::where('email', $email)->first();
                if ($emailExists) {
                    $email = null;
                }
            }

            $user = User::create([
                'firstname' => $name[0] ?? null,
                'lastname' => $name[1] ?? null,
                'email' => $email,
                'ip_address' => getIp(),
                $oauthProvider->alias . '_id' => $id,
            ]);

            if ($user) {
                if ($user->email) {
                    $user->forceFill(['email_verified_at' => Carbon::now()])->save();
                }
                Auth::login($user);
                return redirect()->route('user');
            }

        } catch (\Exception $e) {
            toastr()->error(lang('Authentication failed. Please try again later.', 'auth'));
            return redirect()->route('login');
        }
    }

    /**
     * Display the complete form for OAuth authentication.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showCompleteForm()
    {
        return view('auth.oauth.complete');
    }

    /**
     * Complete the user profile update process.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ] + ReCaptchaValidation::validate());

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $verify = (settings('actions')->email_verification_status && $user->email != $request->email) ? 1 : 0;

        $update = $user->update([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($update) {
            if ($verify) {
                $user->forceFill(['email_verified_at' => null])->save();
                $user->sendEmailVerificationNotification();
            }
            return redirect()->route('user');
        }
    }
}
