<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::all();
        return view('admin.settings.pages.index', ['pages' => $pages]);
    }

    public function slug(Request $request)
    {
        $slug = null;
        if ($request->content != null) {
            $slug = SlugService::createSlug(Page::class, 'slug', $request->content);
        }
        return response()->json(['slug' => $slug]);
    }

    public function create()
    {
        return view('admin.settings.pages.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255', 'min:2'],
            'body' => ['nullable', 'min:2'],
            'short_description' => ['nullable', 'max:200', 'min:2'],
            'slug' => ['required', 'unique:pages', 'alpha_dash'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        if ($request->slug == "contact-us" && !settings('general')->contact_email) {
            toastr()->error(admin_lang('Email is missing, please setup the contact email in general settings'));
            return back();
        }
        $page = Page::create([
            'title' => $request->title,
            'slug' => SlugService::createSlug(Page::class, 'slug', $request->title),
            'body' => $request->body,
            'short_description' => $request->short_description,
        ]);
        if ($page) {
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.settings.pages.edit', $page->id);
        }
    }

    public function edit(Page $page)
    {
        return view('admin.settings.pages.edit', ['page' => $page]);
    }

    public function update(Request $request, Page $page)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255', 'min:2'],
            'body' => ['nullable', 'min:2'],
            'short_description' => ['nullable', 'max:200', 'min:2'],
            'slug' => ['required', 'alpha_dash', 'unique:pages,slug,' . $page->id],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if ($request->slug == "contact-us" && !settings('general')->contact_email) {
            toastr()->error(admin_lang('Email is missing, please setup the contact email in general settings'));
            return back();
        }
        $update = $page->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'body' => $request->body,
            'short_description' => $request->short_description,
        ]);
        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function destroy(Page $page)
    {
        $page->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
