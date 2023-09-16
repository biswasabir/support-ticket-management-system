<?php

use App\Methods\Dotenv;
use App\Models\Extension;
use App\Models\MailTemplate;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;
use Vinkla\Hashids\Facades\Hashids;
use Vironeer\System;

function demoMode()
{
    if (env('DEMO_MODE')) {
        return true;
    }
    return false;
}

function systemInfo()
{
    $system = new System();
    return $system->info();
}

function adminUrl()
{
    return url('/admin');
}

function settings($key = null)
{
    if (!empty($key)) {
        return Settings::selectSettings($key);
    }
    $settings = Settings::pluck('value', 'key')->all();
    return array_to_object($settings);
}

function extension($alias)
{
    $extension = Extension::where('alias', $alias)->first();
    return $extension;
}

function dateFormat($date)
{
    Date::setLocale(app()->getLocale());
    $format = Date::parse($date)->format(Settings::dateFormats()[settings('general')->date_format]);
    return $format;
}

function carbon()
{
    return new Carbon();
}

function imageUpload($file, $location, $size = null, $specificName = null, $old = null)
{
    makeDirectory(public_path($location));
    if (!empty($old)) {
        removeFile($old);
    }
    if (!empty($specificName)) {
        $filename = $specificName . '.' . $file->getClientOriginalExtension();
    } else {
        $filename = Str::random(15) . '_' . time() . '.' . $file->getClientOriginalExtension();
    }
    $image = Image::make($file);
    $width = $image->width();
    $height = $image->height();
    if (!empty($size)) {
        $newSize = explode('x', strtolower($size));
        if ($newSize[0] != $width && $newSize[1] != $height) {
            $image->resize($newSize[0], $newSize[1]);
        }
    }
    $image->save(public_path($location . $filename));
    return $location . $filename;
}

function fileUpload($file, $location, $specificName = null, $old = null)
{
    makeDirectory(public_path($location));
    if (!empty($old)) {
        removeFile($old);
    }
    if (!empty($specificName)) {
        $filename = $specificName . '.' . $file->getClientOriginalExtension();
    } else {
        $filename = Str::random(15) . '_' . time() . '.' . $file->getClientOriginalExtension();
    }
    $file->move(public_path($location), $filename);
    return $location . $filename;
}

function storageUpload($file, $location, $specificName = null, $old = null)
{
    $disk = Storage::disk('public');
    if (!empty($old)) {
        $disk->delete($old);
    }
    if (!empty($specificName)) {
        $filename = $specificName . '.' . $file->getClientOriginalExtension();
    } else {
        $filename = Str::random(15) . '_' . time() . '.' . $file->getClientOriginalExtension();
    }
    $disk->put($location . $filename, fopen($file, 'r+'));
    return $location . $filename;
}

function removeFile($path)
{
    $path = public_path($path);
    if (!file_exists($path)) {
        return true;
    }
    return File::delete($path);
}

function removeDirectory($path)
{
    if (!file_exists($path)) {
        return true;
    }
    return File::deleteDirectory($path);
}

function makeDirectory($path)
{
    if (File::exists($path)) {
        return true;
    }
    return File::makeDirectory($path, 0775, true);
}

function shorterText($text, $chars_limit)
{
    return Str::limit($text, $chars_limit, $end = '...');
}

function setEnv($key, $value, $quote = false)
{
    $env = new Dotenv();
    return $env->setKey($key, $value, $quote);
}

function admin_lang($key)
{
    return $key;
}

function lang($key, $file = null, $lang = null)
{
    $lang = $lang ?? app()->getLocale();
    $file = $file ? Str::slug($file) : 'general';
    $filePath = base_path('lang/' . $lang . '/' . $file . '.php');
    if (!File::exists($filePath)) {
        File::put($filePath, "<?php\n\nreturn [];\n");
    }
    $trans = include $filePath;
    if (!array_key_exists(Str::slug($key, '_'), $trans)) {
        $trans[Str::slug($key, '_')] = $key;
        File::put($filePath, "<?php\n\nreturn " . var_export($trans, true) . ";\n");
    }
    return trans($file . '.' . Str::slug($key, '_'), [], $lang);
}

function mailTemplate($alias)
{
    $mailTemplate = MailTemplate::where('alias', $alias)->first();
    return $mailTemplate;
}

function pageTitle($env)
{
    $name = settings('general')->site_name;
    $title = null;
    $section = null;
    if ($env->yieldContent('section')) {
        $section = ' — ' . $env->yieldContent('section');
    }
    if ($env->yieldContent('title')) {
        $title = ' — ' . $env->yieldContent('title');
    }
    return $name . $section . $title;
}

function chartDates(Carbon $startDate, Carbon $endDate, $format = 'Y-m-d')
{
    $dates = collect();
    $startDate = $startDate->copy();
    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
        $dates->put($date->format($format), 0);
    }
    return $dates;
}

function getIp()
{
    $ip = null;
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
    } else {
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
    }
    return $ip;
}

function hashid($id, $connection = null)
{
    return ($connection) ? Hashids::connection($connection)->encode($id) : Hashids::encode($id);
}

function unhashid($id, $connection = null)
{
    return ($connection) ? Hashids::connection($connection)->decode($id) : Hashids::decode($id);
}

function array_to_object($array)
{
    return json_decode(json_encode($array), false);
}

function array_diff_key_objects($obj1, $obj2)
{
    return array_diff_key((array) $obj1, (array) $obj2);
}
