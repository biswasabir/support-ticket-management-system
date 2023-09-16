<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Partials\Languages;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Validator;

class TranslateController extends Controller
{
    public function index($group = null)
    {
        $languageCode = app()->getLocale();
        $groups = array_map(function ($file) {
            return pathinfo($file)['filename'];
        }, File::files(base_path('lang/' . $languageCode)));
        $active = $group ?? 'general';
        $translates = trans($active, [], $languageCode);
        usort($groups, function ($a, $b) {
            if (strpos($a, 'general') !== false && strpos($b, 'general') === false) {
                return -1;
            } else if (strpos($a, 'general') === false && strpos($b, 'general') !== false) {
                return 1;
            } else {
                return 0;
            }
        });
        $defaultLanguage = trans($active, [], $languageCode);
        return view('admin.settings.translates', [
            'active' => $active,
            'groups' => $groups,
            'translates' => $translates,
            'defaultLanguage' => $defaultLanguage,
        ]);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'language' => ['required', 'string', 'max:2'],
            'direction' => ['required', 'string', 'max:3', 'in:ltr,rtl'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $defaultLocale = app()->getLocale();
        try {
            if (!array_key_exists($request->language, Languages::all())) {
                toastr()->error(admin_lang('Invalid language name'));
                return back();
            }
            $languageGroupFile = base_path('lang/' . $defaultLocale . '/' . $request->group . '.php');
            if (!file_exists($languageGroupFile)) {
                toastr()->error(admin_lang('Language group file not exists'));
                return back();
            }
            $translations = include $languageGroupFile;
            foreach ($request->translates as $key1 => $value1) {
                if (is_array($value1)) {
                    foreach ($value1 as $key2 => $value2) {
                        if (!array_key_exists($key2, $value1)) {
                            toastr()->error(admin_lang('Translations error'));
                            return back();
                        }
                    }
                } else {
                    if (!array_key_exists($key1, $translations)) {
                        toastr()->error(admin_lang('Translations error ' . $key1));
                        return back();
                    }
                }
            }
            $fileContent = "<?php \n return " . var_export($request->translates, true) . ";";
            File::put($languageGroupFile, $fileContent);
            if ($request->language != $defaultLocale) {
                $this->createNewLanguageFiles($request->language);
                setEnv('DEFAULT_LANGUAGE', $request->language);
            }
            if ($request->direction != config('app.direction')) {
                setEnv('DEFAULT_DIRECTION', $request->direction);
            }
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    protected function createNewLanguageFiles($newLanguageCode)
    {
        try {
            $defaultLanguage = app()->getLocale();
            $langPath = base_path('lang/');
            if (!File::exists($langPath . $newLanguageCode)) {
                File::makeDirectory($langPath . $newLanguageCode);
                $defaultLanguageFiles = File::allFiles($langPath . $defaultLanguage);
                foreach ($defaultLanguageFiles as $file) {
                    $newFile = $langPath . $newLanguageCode . '/' . $file->getFilename();
                    if (!File::exists($newFile)) {
                        File::copy($file, $newFile);
                    }
                }
            }
            File::deleteDirectory(base_path('lang/' . $defaultLanguage));
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
