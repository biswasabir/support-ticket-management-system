<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->with(['articles' => function ($query) {
            $query->limit(6);
        }])->get();
        return view('knowledgebase.index', ['categories' => $categories]);
    }

    public function search(Request $request)
    {
        $searchTerm = '%' . $request->q . '%';
        $articles = Article::where('title', 'like', $searchTerm)
            ->OrWhere('slug', 'like', $searchTerm)
            ->OrWhere('body', 'like', $searchTerm)
            ->OrWhere('short_description', 'like', $searchTerm)
            ->select('slug', 'title', 'short_description')
            ->orWhereHas('categories', function ($query) use ($searchTerm) {
                $query->where('categories.name', 'like', $searchTerm);
            })
            ->get();
        $output = '';
        if ($articles->count() > 0) {
            foreach ($articles as $article) {
                $output .= '<a href="' . route('knowledgebase.article', $article->slug) . '" class="search-item">
                <div class="mt-1 me-3">
                    <i class="fa fa-file-alt fa-2x"></i>
                </div>
                <div>
                    <h6 class="search-item-title mb-1">' . $article->title . '</h6>
                    <p class="search-item-text text-muted">' . $article->short_description . '</p>
                </div>
            </a>';
            }
        } else {
            $output .= '<div class="empty text-center py-4">' . lang('No results found', 'knowledgebase') . '</div>';
        }
        return $output;
    }

    public function searchPage()
    {
        $searchTerm = '%' . request('q') . '%';
        $articles = Article::where('title', 'like', $searchTerm)
            ->OrWhere('slug', 'like', $searchTerm)
            ->OrWhere('body', 'like', $searchTerm)
            ->OrWhere('short_description', 'like', $searchTerm)
            ->orWhereHas('categories', function ($query) use ($searchTerm) {
                $query->where('categories.name', 'like', $searchTerm);
            })
            ->select('slug', 'title', 'short_description');
        $articles = $articles->paginate(30);
        $articles->appends(request()->only(['q']));
        return view('knowledgebase.search', ['articles' => $articles]);
    }

    public function categories()
    {
        $categories = Category::withCount('articles')->paginate(20);
        return view('knowledgebase.categories', ['categories' => $categories]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->with('articles')->firstOrFail();
        $articles = $category->articles()->paginate(10);
        return view('knowledgebase.category', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }

    public function articles()
    {
        $articles = Article::with('categories')->paginate(10);
        return view('knowledgebase.articles', ['articles' => $articles]);
    }

    public function article($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        return view('knowledgebase.article', ['article' => $article]);
    }

    public function react(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        if ($request->action == 1) {
            $article->increment('likes');
        } elseif ($request->action == 2) {
            $article->increment('dislikes');
        }
        return response()->json([
            'success' => lang('Thanks for your feedback', 'knowledgebase'),
        ]);
    }
}
