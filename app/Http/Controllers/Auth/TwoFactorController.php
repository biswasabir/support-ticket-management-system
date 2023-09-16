<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class TwoFactorController extends Controller
{
    public function show2FaVerifyForm()
    {
        if (!auth()->user()->google2fa_status || session()->has('2fa')
            && session('2fa') == encrypt(auth()->user()->id)) {
            return redirect()->route('user');
        }
        return view('auth.2fa');
    }

    public function verify2fa(Request $request)
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
        $valid = $google2fa->verifyKey(auth()->user()->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(lang('Invalid OTP code', 'auth'));
            return back();
        }

        session()->put('2fa', encrypt(auth()->user()->id));
        return redirect()->route('user');
    }
}
