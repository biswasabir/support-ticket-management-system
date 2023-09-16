<?php

namespace App\Http\Controllers\Admin\Extra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomCssController extends Controller
{
    public function index()
    {
        $cssFile = @file_get_contents(public_path(config('vironeer.css.custom')));
        return view('admin.extra.custom-css', ['cssFile' => $cssFile]);
    }

    public function update(Request $request)
    {
        $cssFile = public_path(config('vironeer.css.custom'));
        if (!file_exists($cssFile)) {
            fopen($cssFile, "w");
        }
        file_put_contents($cssFile, $request->cssContent);
        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }
}
