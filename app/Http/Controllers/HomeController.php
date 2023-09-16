<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->with(['articles' => function ($query) {
            $query->limit(6);
        }])->get();
        return view('home', ['categories' => $categories]);
    }
}
