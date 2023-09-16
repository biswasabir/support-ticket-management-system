<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Validator;

class PanelStyleController extends Controller
{
    public function index()
    {
        return view('admin.system.panel-style');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'system.colors.*' => 'required|regex:/^#[A-Fa-f0-9]{6}$/',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {toastr()->error($error);}
            return back();
        }

        $requestData = $request->except('_token');

        foreach ($requestData as $key => $value) {
            $update = Settings::updateSettings($key, $value);
            if (!$update) {
                toastr()->error(admin_lang('Updated Error'));
                return back();
            }
        }

        $colors = ':root {' . PHP_EOL;
        foreach ($requestData['system']['colors'] as $key => $value) {
            $colors .= '  --' . $key . ':' . $value . ';' . PHP_EOL;
        }
        $colors .= '}' . PHP_EOL;

        $colorsFile = public_path(config('vironeer.colors.admin'));
        if (!file_exists($colorsFile)) {
            fopen($colorsFile, "w");
        }
        file_put_contents($colorsFile, $colors);

        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }
}
