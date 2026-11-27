<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\ArticleCrudController;
use App\Http\Controllers\Admin\CategoryCrudController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [HomeController::class, 'blog'])->name('blog');

Route::get('/category', [HomeController::class, 'categoryList'])->name('category-list');

Route::get('/{slug}', [HomeController::class, 'articleDetail'])->name('article-detail');

Route::get('/category/{slug}', [HomeController::class, 'categoryDetail'])->name('category-detail');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    //Admin routes  article
    Route::get('/articles', [ArticleCrudController::class, 'index'])->name('article-index');

    Route::get('/articles/create', [ArticleCrudController::class, 'create'])->name('article-create');
    Route::post('/articles/store', [ArticleCrudController::class, 'store'])->name('article-store');

    Route::get('/articles/{id}/edit', [ArticleCrudController::class, 'edit'])->name('article-edit');
    Route::post('/articles/{id}', [ArticleCrudController::class, 'update'])->name('article-update');

    Route::post('/articles/{id}/delete', [ArticleCrudController::class, 'destroy'])->name('article-destroy');


    //Admin routes  categories
    Route::get('/admin/categories', [CategoryCrudController::class, 'index'])->name('category-index');

    Route::get('/admin/categories/create', [CategoryCrudController::class, 'create'])->name('category-create');
    Route::post('/admin/categories/store', [CategoryCrudController::class, 'store'])->name('category-store');

    Route::get('/admin/categories/{$id}/edit', [CategoryCrudController::class, 'edit'])->name('category-edit');
    Route::post('/admin/categories/{$id}', [CategoryCrudController::class, 'update'])->name('category-update');

    Route::post('/admin/categories/{id}/delete', [CategoryCrudController::class, 'destroy'])->name('category-destroy');
});
