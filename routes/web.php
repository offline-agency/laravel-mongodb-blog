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

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Auth'], function () {
    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');
});

Route::get('/', 'HomeController@blog')->name('blog');

Route::get('/category', 'HomeController@categoryList')->name('category-list');

Route::get('/{slug}', 'HomeController@articleDetail')->name('article-detail');

Route::get('/category/{slug}', 'HomeController@categoryDetail')->name('category-detail');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => 'auth'], function () {

    // Dashboard
    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');

    //Admin routes  article
    Route::get('/articles', 'ArticleCrudController@index')->name('article-index');

    Route::get('/articles/create', 'ArticleCrudController@create')->name('article-create');
    Route::post('/articles/store', 'ArticleCrudController@store')->name('article-store');

    Route::get('/articles/{id}/edit', 'ArticleCrudController@edit')->name('article-edit');
    Route::post('/articles/{id}', 'ArticleCrudController@update')->name('article-update');

    Route::post('/articles/{id}/delete', 'ArticleCrudController@destroy')->name('article-destroy');


    //Admin routes  categories
    Route::get('/admin/categories', 'CategoryCrudController@index')->name('category-index');

    Route::get('/admin/categories/create', 'CategoryCrudController@create')->name('category-create');
    Route::post('/admin/categories/store', 'CategoryCrudController@store')->name('category-store');

    Route::get('/admin/categories/{$id}/edit', 'CategoryCrudController@edit')->name('category-edit');
    Route::post('/admin/categories/{$id}', 'CategoryCrudController@update')->name('category-update');

    Route::post('/admin/categories/{id}/delete', 'CategoryCrudController@destroy')->name('category-destroy');
});
