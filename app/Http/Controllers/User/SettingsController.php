<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        $QR_Image = null;
        if (!$this->user()->google2fa_status) {
            $google2fa = app('pragmarx.google2fa');
            $secretKey = encrypt($google2fa->generateSecretKey());
            $this->user()->update(['google2fa_secret' => $secretKey]);
            $QR_Image = $google2fa->getQRCodeInline(settings('general')->site_name, $this->user()->email, $this->user()->google2fa_secret);
        }
        return view('user.settings', ['user' => $this->user(), 'QR_Image' => $QR_Image]);
    }

    public function detailsUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email,' . $this->user()->id],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $verify = (settings('actions')->email_verification_status && $this->user()->email != $request->email) ? 1 : 0;
        if ($request->has('avatar')) {
            $avatar = imageUpload($request->file('avatar'), 'images/avatars/users/', '120x120', null, $this->user()->avatar);
        } else {
            $avatar = $this->user()->avatar;
        }
        $user = $this->user()->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'avatar' => $avatar,
        ]);
        if ($user) {
            if ($verify) {
                $this->user()->forceFill(['email_verified_at' => null])->save();
                $this->user()->sendEmailVerificationNotification();
            }
            toastr()->success(lang('Account details has been updated successfully', 'settings'));
            return back();
        }
    }

    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current-password' => ['required'],
            'new-password' => ['required', 'string', 'min:8', 'confirmed'],
            'new-password_confirmation' => ['required'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if (!(Hash::check($request->get('current-password'), $this->user()->password))) {
            toastr()->error(lang('Your current password does not matches with the password you provided', 'passwords'));
            return back();
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            toastr()->error(lang('New Password cannot be same as your current password. Please choose a different password', 'passwords'));
            return back();
        }
        $update = $this->user()->update([
            'password' => bcrypt($request->get('new-password')),
        ]);
        if ($update) {
            toastr()->success(lang('Account password has been changed successfully', 'settings'));
            return back();
        }
    }

    public function towFactorEnable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_code' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($this->user()->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(lang('Invalid OTP code', 'settings'));
            return back();
        }
        $update2FaStatus = $this->user()->update(['google2fa_status' => true]);
        if ($update2FaStatus) {
            session()->put('2fa', encrypt($this->user()->id));
            toastr()->success(lang('2FA Authentication has been enabled successfully', 'settings'));
            return back();
        }

    }

    public function towFactorDisable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_code' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($this->user()->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(lang('Invalid OTP code', 'settings'));
            return back();
        }
        $update2FaStatus = $this->user()->update(['google2fa_status' => false]);
        if ($update2FaStatus) {
            if ($request->session()->has('2fa')) {
                session()->forget('2fa');
            }
            toastr()->success(lang('2FA Authentication has been disabled successfully', 'settings'));
            return back();
        }
    }

    private function user()
    {
        return Auth::user();
    }
}
