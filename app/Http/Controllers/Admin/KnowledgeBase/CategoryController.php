<?php

namespace App\Http\Controllers\Admin\KnowledgeBase;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        return view('admin.knowledgebase.categories.index', ['categories' => $categories]);
    }

    public function slug(Request $request)
    {
        $slug = null;
        if ($request->content != null) {
            $slug = SlugService::createSlug(Category::class, 'slug', $request->content);
        }
        return response()->json(['slug' => $slug]);
    }

    public function create()
    {
        return view('admin.knowledgebase.categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'icon' => ['required', 'image', 'mimes:png,jpg,jpeg'],
            'name' => ['required', 'max:255', 'min:2'],
            'slug' => ['required', 'unique:categories', 'alpha_dash'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }
        $icon = imageUpload($request->file('icon'), 'images/knowledgebase/categories/', '150x150');
        if ($icon) {
            $category = Category::create([
                'name' => $request->name,
                'slug' => SlugService::createSlug(Category::class, 'slug', $request->name),
                'icon' => $icon,
            ]);
            if ($category) {
                toastr()->success(admin_lang('Created Successfully'));
                return redirect()->route('admin.knowledgebase.categories.edit', $category->id);
            }
        } else {
            toastr()->error(admin_lang('Upload error'));
            return back();
        }
    }

    public function edit(Category $category)
    {
        return view('admin.knowledgebase.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'icon' => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
            'name' => ['required', 'max:255', 'min:2'],
            'slug' => ['required', 'alpha_dash', 'unique:categories,slug,' . $category->id],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if ($request->has('icon')) {
            $icon = imageUpload($request->file('icon'), 'images/knowledgebase/categories/', '150x150', null, $category->icon);
        } else {
            $icon = $category->icon;
        }
        if ($icon) {
            $update = $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'icon' => $icon,
            ]);
            if ($update) {
                toastr()->success(admin_lang('Updated Successfully'));
                return back();
            }
        } else {
            toastr()->error(admin_lang('Upload error'));
            return back();
        }
    }

    public function destroy(Category $category)
    {
        $category->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
