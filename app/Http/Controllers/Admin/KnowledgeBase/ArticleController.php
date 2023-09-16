<?php

namespace App\Http\Controllers\Admin\KnowledgeBase;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Validator;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $articles = Article::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $articles->where('title', 'like', $searchTerm)
                ->OrWhere('slug', 'like', $searchTerm)
                ->OrWhere('body', 'like', $searchTerm)
                ->OrWhere('short_description', 'like', $searchTerm)
                ->orWhereHas('categories', function ($query) use ($searchTerm) {
                    $query->where('categories.name', 'like', $searchTerm);
                });
        }

        if (request()->filled('category')) {
            $categoryId = request('category');
            $articles->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }

        $articles = $articles->with('categories')
            ->orderbyDesc('id')
            ->paginate(20);

        $articles->appends(request()->only(['search', 'category']));

        return view('admin.knowledgebase.articles.index', [
            'categories' => $categories,
            'articles' => $articles,
        ]);
    }

    public function slug(Request $request)
    {
        $slug = null;
        if ($request->content != null) {
            $slug = SlugService::createSlug(Article::class, 'slug', $request->content);
        }
        return response()->json(['slug' => $slug]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.knowledgebase.articles.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'slug' => ['required', 'unique:articles', 'alpha_dash'],
            'categories' => ['required', 'array'],
            'body' => ['required', 'string'],
            'short_description' => ['required', 'string', 'max:200', 'min:2'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        foreach ($request->categories as $category) {
            $checkExist = Category::find($category);
            if (!$checkExist) {
                toastr()->error(admin_lang('Invalid category'));
                return back();
            }
        }

        $article = Article::create([
            'title' => $request->title,
            'slug' => SlugService::createSlug(Article::class, 'slug', $request->title),
            'body' => $request->body,
            'short_description' => $request->short_description,
        ]);

        if ($article) {
            $article->categories()->attach($request->categories);
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.knowledgebase.articles.edit', $article->id);
        }
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        $categoryIds = $article->categories->pluck('id')->toArray();
        return view('admin.knowledgebase.articles.edit', [
            'article' => $article,
            'categories' => $categories,
            'categoryIds' => $categoryIds,
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'slug' => ['required', 'alpha_dash', 'unique:articles,slug,' . $article->id],
            'categories' => ['required', 'array'],
            'body' => ['required', 'string'],
            'short_description' => ['required', 'string', 'max:200', 'min:2'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        foreach ($request->categories as $category) {
            $checkExist = Category::find($category);
            if (!$checkExist) {
                toastr()->error(admin_lang('Invalid category'));
                return back();
            }
        }

        $update = $article->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'body' => $request->body,
            'short_description' => $request->short_description,
        ]);

        if ($update) {
            $article->categories()->sync($request->categories);
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function destroy(Article $article)
    {
        $article->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
