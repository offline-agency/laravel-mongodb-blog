<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $categories_counter = Category::all()->count();
        $articles_counter = Article::all()->count();
        $users_counter = User::all()->count();

        return view('admin.dashboard')->with([
            'articles_counter' => $articles_counter,
            'categories_counter' => $categories_counter,
            'users_counter' => $users_counter
        ]);
    }
}
