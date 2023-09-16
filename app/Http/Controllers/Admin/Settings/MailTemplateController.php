<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\MailTemplate;
use Illuminate\Http\Request;
use Validator;

class MailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $mailTemplates = MailTemplate::all();
        return view('admin.settings.mail-templates.index', [
            'mailTemplates' => $mailTemplates,
        ]);
    }

    public function edit(Request $request, MailTemplate $mailTemplate)
    {
        return view('admin.settings.mail-templates.edit', ['mailTemplate' => $mailTemplate]);
    }

    public function update(Request $request, MailTemplate $mailTemplate)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {toastr()->error($error);}
            return back();
        }
        if (!$mailTemplate->isDefault()) {
            $request->status = ($request->has('status')) ? 1 : 0;
        } else {
            $request->status = 1;
        }
        $update = $mailTemplate->update([
            'subject' => $request->subject,
            'status' => $request->status,
            'body' => $request->body,
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }
}
