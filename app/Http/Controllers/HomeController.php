<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function blog()
    {
        $articles = Article::orderBy('publication_date', 'desc')->simplePaginate(10);

        return view('blog.index')->with([
            'articles' => $articles
        ]);
    }

    public function articleDetail($slug)
    {
        $article = Article::where('slug.' . cl(), $slug)->first();

        if (!(is_null($article))) {
            return view('blog.detail')->with([
                'article' => $article
            ]);
        }else{
            abort(404);
        }
    }

    public function categoryList()
    {
        $categories = Category::all();

        return view('category.list')->with([
           'categories' => $categories
        ]);
    }

    public function categoryDetail($slug)
    {
        $category = Category::where('slug.' . cl(), $slug)->first();

        $articles = Article::where('categories.slug.' . cl(), $slug)->simplePaginate(10);

        return view('category.detail')->with([
            'category' => $category,
            'articles' => $articles
        ]);
    }
}
