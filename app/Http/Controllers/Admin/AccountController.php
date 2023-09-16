<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Validator;

class AccountController extends Controller
{
    public function index()
    {
        $qrCode = null;
        if (!$this->user()->google2fa_status) {
            $google2fa = app('pragmarx.google2fa');
            $secretKey = encrypt($google2fa->generateSecretKey());
            $this->user()->update(['google2fa_secret' => $secretKey]);
            $qrCode = $google2fa->getQRCodeInline(settings('general')->site_name, $this->user()->email, $this->user()->google2fa_secret);
        }
        return view('admin.account', [
            'user' => $this->user(),
            'qrCode' => $qrCode,
        ]);
    }

    public function updateDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'block_patterns', 'max:255'],
            'lastname' => ['required', 'string', 'block_patterns', 'max:255'],
            'avatar' => ['image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'email' => ['required', 'email', 'block_patterns', 'unique:users,email,' . $this->user()->id],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if ($request->has('avatar')) {
            $uploadAvatar = imageUpload($request->file('avatar'), 'images/avatars/', '120x120', null, $this->user()->avatar);
        } else {
            $uploadAvatar = $this->user()->avatar;
        }
        $update = $this->user()->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'avatar' => $uploadAvatar,
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current-password' => ['required'],
            'new-password' => ['required', 'string', 'min:6', 'confirmed'],
            'new-password_confirmation' => ['required'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if (!(Hash::check($request->get('current-password'), $this->user()->password))) {
            toastr()->error(admin_lang('Your current password does not matches with the password you provided.'));
            return back();
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            toastr()->error(admin_lang('New Password cannot be same as your current password. Please choose a different password.'));
            return back();
        }
        $update = $this->user()->update([
            'password' => bcrypt($request->get('new-password')),
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function enable2FA(Request $request)
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
            toastr()->error(admin_lang('Invalid OTP code'));
            return back();
        }
        $update2FaStatus = $this->user()->update(['google2fa_status' => true]);
        if ($update2FaStatus) {
            session()->put('2fa', encrypt($this->user()->id));
            toastr()->success(admin_lang('2FA Authentication has been enabled successfully'));
            return back();
        }
    }

    public function disable2FA(Request $request)
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
            toastr()->error(admin_lang('Invalid OTP code'));
            return back();
        }
        $update2FaStatus = $this->user()->update(['google2fa_status' => false]);
        if ($update2FaStatus) {
            if ($request->session()->has('2fa')) {
                session()->forget('2fa');
            }
            toastr()->success(admin_lang('2FA Authentication has been disabled successfully'));
            return back();
        }
    }

    private function user()
    {
        return Auth::user();
    }
}
