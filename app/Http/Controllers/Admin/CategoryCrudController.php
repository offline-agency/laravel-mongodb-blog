<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryCrudController extends Controller
{
    //List of all categories
    public function index()
    {
        $categories = Category::orderBy('publication_date', 'desc')->simplePaginate(10);

        return view('admin.categories.index')->with([
            'categories' => $categories
        ]);
    }

    //Create Category
    public function create()
    {
        return view('admin.categories.create')->with([
          //
        ]);
    }

    //Store Category
    public function store(Request $request)
    {
        $arr = [];

        $category = new Category;
        $arr['slug'] = Str::slug($request->input('name'));

        $category->storeWithSync($request, $arr);

        return view('admin.categories.index');

    }

    //Edit Category
    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.categories.edit')->with([
            'category' => $category
        ]);
    }

    //Update Category
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        $arr = [];

        $arr['slug'] = Str::slug($request->input('name'));
        $arr['articles'] = processRelatedArticles($category->articles);

        $category->updateWithSync($request, $arr);

        return view('admin.categories.index');
    }

    //Destroy Category
    public function destroy($id)
    {
        $category = Category::find($id);

        $category->save();

        return view('admin.categories.index');
    }
}




