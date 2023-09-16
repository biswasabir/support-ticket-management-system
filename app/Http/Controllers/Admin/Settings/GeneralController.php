<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Str;
use Validator;

class GeneralController extends Controller
{
    public function index()
    {
        return view('admin.settings.general');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'general.site_name' => 'required|string|block_patterns|max:255',
            'general.site_url' => 'required|url',
            'general.date_format' => 'required|in:' . implode(',', array_keys(Settings::dateFormats())),
            'general.timezone' => 'required|in:' . implode(',', array_keys(Settings::timezones())),
            'general.contact_email' => 'nullable|email|block_patterns',
            'general.terms_link' => 'nullable|string',
            'seo.title' => 'nullable|string|block_patterns|max:70',
            'seo.description' => 'nullable|string|block_patterns|max:150',
            'seo.keywords' => 'nullable|string|block_patterns|max:200',
            'tickets.file_types' => 'required|string',
            'tickets.max_files' => 'required|integer|min:1|max:1000',
            'tickets.max_file_size' => 'required|integer|min:1',
            'colors.*' => 'required|regex:/^#[A-Fa-f0-9]{6}$/',
            'media.logo_dark' => 'nullable|mimes:png,jpg,jpeg,svg',
            'media.logo_light' => 'nullable|mimes:png,jpg,jpeg,svg',
            'media.favicon' => 'nullable|mimes:png,jpg,jpeg,ico',
            'media.social_image' => 'nullable|mimes:jpg,jpeg',
            'media.header_pattern' => 'nullable|mimes:svg',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {toastr()->error($error);}
            return back();
        }

        if ($request->has('action.email_verification_status') && !settings('smtp')->status) {
            toastr()->error(admin_lang('SMTP is not enabled'));
            return back()->withInput();
        }

        $requestData = $request->except('_token');

        if ($request->has('media.logo_dark')) {
            $filename = 'logo-dark';
            $darkLogo = fileUpload($request->file('media.logo_dark'), 'images/', $filename, settings('media')->logo_dark);
            $requestData['media']['logo_dark'] = $darkLogo;
        } else {
            $requestData['media']['logo_dark'] = settings('media')->logo_dark;
        }

        if ($request->has('media.logo_light')) {
            $filename = 'logo-light';
            $lightLogo = fileUpload($request->file('media.logo_light'), 'images/', $filename, settings('media')->logo_light);
            $requestData['media']['logo_light'] = $lightLogo;
        } else {
            $requestData['media']['logo_light'] = settings('media')->logo_light;
        }

        if ($request->has('media.favicon')) {
            $filename = 'favicon';
            $favicon = fileUpload($request->file('media.favicon'), 'images/', $filename, settings('media')->favicon);
            $requestData['media']['favicon'] = $favicon;
        } else {
            $requestData['media']['favicon'] = settings('media')->favicon;
        }

        if ($request->has('media.social_image')) {
            $ogImage = imageUpload($request->file('media.social_image'), 'images/', '600x315', null, settings('media')->social_image);
            $requestData['media']['social_image'] = $ogImage;
        } else {
            $requestData['media']['social_image'] = settings('media')->social_image;
        }

        if ($request->has('media.header_pattern')) {
            $headerPattern = fileUpload($request->file('media.header_pattern'), 'images/', null, settings('media')->header_pattern);
            $requestData['media']['header_pattern'] = $headerPattern;
        } else {
            $requestData['media']['header_pattern'] = settings('media')->header_pattern;
        }

        $requestData['actions']['email_verification_status'] = ($request->has('actions.email_verification_status')) ? 1 : 0;
        $requestData['actions']['registration_status'] = ($request->has('actions.registration_status')) ? 1 : 0;
        $requestData['actions']['home_page_status'] = ($request->has('actions.home_page_status')) ? 1 : 0;
        $requestData['actions']['knowledgebase_status'] = ($request->has('actions.knowledgebase_status')) ? 1 : 0;
        $requestData['actions']['gdpr_cookie_status'] = ($request->has('actions.gdpr_cookie_status')) ? 1 : 0;
        $requestData['actions']['force_ssl_status'] = ($request->has('actions.force_ssl_status')) ? 1 : 0;

        foreach ($requestData as $key => $value) {
            $update = Settings::updateSettings($key, $value);
            if (!$update) {
                toastr()->error(admin_lang('Updated Error'));
                return back();
            }
        }

        setEnv('APP_NAME', Str::slug($requestData['general']['site_name'], '_'));
        setEnv('APP_URL', $requestData['general']['site_url']);
        setEnv('APP_TIMEZONE', $requestData['general']['timezone'], true);

        $colors = ':root {' . PHP_EOL;

        foreach ($requestData['colors'] as $key => $value) {
            $colors .= '  --' . $key . ':' . $value . ';' . PHP_EOL;
        }

        $colors .= '}' . PHP_EOL;

        $colorsFile = public_path(config('vironeer.colors.front'));
        if (!file_exists($colorsFile)) {
            fopen($colorsFile, "w");
        }
        file_put_contents($colorsFile, $colors);

        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }
}
