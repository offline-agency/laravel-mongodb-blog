<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Jobs\PublishArticleJob;
use App\Models\Article;
use App\Models\Category;
use App\Models\ArticleTag;
use App\Models\Auth\User\User;
use App\Models\State;
use App\Models\Visibility;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class ArticleController extends BackendController
{
    /**
     * ArticleController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->middleware( 'admin', [ 'except' => [ 'index', 'failed', 'clearValidationCache' ] ] );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at');

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $articlecategories = Category::all();
        $articletags = ArticleTag::all();
        $states = State::all();
        $visibilities = Visibility::all();
        $users = User::role('Author')->get();

        return view('admin.articles.create', compact(['articlecategories', 'articletags', 'states', 'users', 'visibilities']));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $arr = [];
        $article = new Article;
        $arr['autoincrement_id'] = getAID($article);
        $arr['slug'] = str_slug($request->title) . '-' . $arr['autoincrement_id'];
        $arr['primarycategory'] = getPrimaryRequest($request->categorylistdata);
        $arr['status'] = $request->status;
        $arr['visibility'] = $request->visibility;
        $arr['last_updated_by'] = auth()->user()->username;
        $arr['publication_date'] = getPublicationDate($request->status, "");
        $article->storeWithSync($request, $arr);

        return redirect()->route('admin.articles.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $article = new Article;
        $article = $article->find($id);
        $articlecategories = Category::all();
        $articletags = ArticleTag::all();
        $states = State::all();
        $visibilities = Visibility::all();

        $users = User::role('Author')->get();

        // put primarycategory in first position
        if (!is_null($article->primarycategory)) {
            $primarycat = $article->primarycategory;
            $catid = $primarycat->ref_id;
            $primarycat = Category::where('_id', $catid)->first();
            $articlecategories = Category::where('_id', '!=', $catid)->orderBy('name', 'asc')->get();
            $articlecategories = $articlecategories->prepend($primarycat);
        }

        return view('admin.articles.edit', compact([
            'article',
            'articlecategories',
            'articletags',
            'states',
            'visibilities',
            'users'
        ]));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $arr = [];
        $article = new Article;
        $article = $article->find($id);
        $arr['autoincrement_id'] = $article->autoincrement_id;
        $arr['primarycategory'] = getPrimaryRequest($request->input('categorylistdata'));
        $arr['slug'] = str_slug($request->title) . '-' . $arr['autoincrement_id'];
        $arr['status'] = $request->status;
        $arr['visibility'] = $request->visibility;
        $arr['last_updated_by'] = auth()->user()->username;
        $arr['publication_date'] = getPublicationDate($request->status, $article->publication_date);
        //dd($arr, $request->all());

        $article->updateWithSync($request, $arr);

        if($arr['status'] == 'planned'){
            PublishArticleJob::dispatch($article->id)->delay(now()->addMinutes(now()->diffInMinutes(new Carbon($article->planned_date . ' ' . $article->planned_time . ':00'))));
        }
        return redirect()->route('admin.articles.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $article = new Article;
        $article = $article->find($id);
        $article->destroyWithSync();

        return redirect()->route('admin.articles.index');
    }
}
