<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleCrudController extends Controller
{
    //List of all articles
    public function index()
    {
        $articles = Article::orderBy('publication_date', 'desc')->simplePaginate(10);

        return view('admin.articles.index')->with([
            'articles' => $articles
        ]);
    }


    //Create Article
    public function create()
    {
        $categories = Category::all();

        return view('admin.articles.create')->with([
            'categories' => $categories
        ]);
    }


    //Store Article
    public function store(Request $request)
    {
        $arr = [];

        $article = new Article;

        $arr['autoincrement_id'] = getAID( $article );
        $arr['slug'] = Str::slug($arr['title']) . "-" .  $arr['autoincrement_id'];

        $article->storeWithSync($request, $arr);

        return redirect()->route('admin.article-index');

    }

    //Edit Article
    public function edit($id)
    {
        $article = Article::find($id);
        $categories = Category::all();

        return view('admin.articles.edit')->with([
            'article' => $article,
            'categories' => $categories
        ]);
    }

    //Update Article
    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        $arr = [];
        $arr['autoincrement_id'] = getAID( $article );
        $arr['slug'] = Str::slug($arr['title']) . "-" .  $arr['autoincrement_id'];

        $article->updateWithSync($request, $arr);

        return redirect()->route('admin.article.index');
    }

    //Destroy Article
    public function destroy($id)
    {
        $article = Article::find($id);

        $article->destroyWithSync();

        return view('admin.article.index');
    }
}
