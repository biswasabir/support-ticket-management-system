<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Methods\ReCaptchaValidation;
use App\Models\Page;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Validator;

class GlobalController extends Controller
{
    public function cookie()
    {
        Cookie::queue('cookie_accepted', true, time() + 31556926);
        return response()->json(['success' => lang('Cookie accepted successfully')]);
    }

    public function popup()
    {
        Cookie::queue('popup_closed', true, time() + 31556926);
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('page', ['page' => $page]);
    }

    public function sendMessage(Request $request, $slug)
    {
        $page = Page::where('slug', 'contact-us')->firstOrFail();
        if (!settings('smtp')->status || !settings('general')->contact_email) {
            toastr()->error(lang('Sending emails is not available right now', 'contact'));
            return back();
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ] + ReCaptchaValidation::validate());
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        try {
            $name = $request->name;
            $email = $request->email;
            $subject = $request->subject;
            $msg = str_ireplace("\r\n", "<br/>", $request->message);
            \Mail::send([], [], function ($message) use ($msg, $email, $subject, $name) {
                $message->to(settings('general')->contact_email)
                    ->from(env('MAIL_FROM_ADDRESS'), $name)
                    ->replyTo($email)
                    ->subject($subject)
                    ->html($msg);
            });
            toastr()->success(lang('Your message has been sent successfully', 'contact'));
            return back();
        } catch (Exception $e) {
            toastr()->error(lang('Sending failed', 'contact'));
            return back()->withInput();
        }
    }
}
