<?php

namespace Tests\Feature;

use Tests\Concerns\SeedsBlog;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use SeedsBlog;

    public function test_guests_are_redirected_to_login_from_every_admin_route(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/articles')->assertRedirect('/login');
        $this->get('/admin/articles/create')->assertRedirect('/login');
        $this->get('/admin/articles/abc/edit')->assertRedirect('/login');
        $this->post('/admin/articles/store')->assertRedirect('/login');
        $this->post('/admin/articles/abc')->assertRedirect('/login');
        $this->post('/admin/articles/abc/delete')->assertRedirect('/login');
        $this->get('/admin/admin/categories')->assertRedirect('/login');
        $this->get('/admin/admin/categories/create')->assertRedirect('/login');
        $this->post('/admin/admin/categories/store')->assertRedirect('/login');
        $this->post('/admin/admin/categories/abc/delete')->assertRedirect('/login');
    }

    public function test_dashboard_shows_the_counters(): void
    {
        $user = $this->seedUser();
        $category = $this->seedCategory('Travel');
        $this->seedArticle($category);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertViewHas('users_counter', 1)
            ->assertViewHas('categories_counter', 1)
            ->assertViewHas('articles_counter', 1);
    }

    public function test_admin_article_index_lists_articles(): void
    {
        $user = $this->seedUser();
        $this->seedArticle($this->seedCategory('Travel'), 'Hello Mongo');

        $this->actingAs($user)
            ->get('/admin/articles')
            ->assertOk()
            ->assertViewHas('articles', fn ($articles) => $articles->count() === 1);
    }

    public function test_admin_article_create_form_is_shown(): void
    {
        $this->seedCategory('Travel');

        $this->actingAs($this->seedUser())
            ->get('/admin/articles/create')
            ->assertOk()
            ->assertViewHas('categories', fn ($categories) => $categories->count() === 1);
    }

    public function test_admin_article_edit_form_is_shown(): void
    {
        $user = $this->seedUser();
        $article = $this->seedArticle($this->seedCategory('Travel'), 'Hello Mongo');

        $this->actingAs($user)
            ->get('/admin/articles/'.$article->id.'/edit')
            ->assertOk()
            ->assertViewHas('article', fn ($viewArticle) => $viewArticle->id === $article->id)
            ->assertViewHas('categories', fn ($categories) => $categories->count() === 1);
    }

    public function test_admin_category_index_is_shown(): void
    {
        $user = $this->seedUser();
        $this->seedCategory('Travel');

        $this->actingAs($user)
            ->get('/admin/admin/categories')
            ->assertOk()
            ->assertViewHas('categories', fn ($categories) => $categories->count() === 1);
    }

    public function test_admin_category_create_form_is_shown(): void
    {
        $this->actingAs($this->seedUser())
            ->get('/admin/admin/categories/create')
            ->assertOk();
    }

    public function test_admin_category_store_creates_a_category(): void
    {
        $user = $this->seedUser();

        $this->actingAs($user)
            ->post('/admin/admin/categories/store', [
                'name' => 'Food',
                'description' => 'About food',
                'media_path' => 'dog.jpeg',
            ])
            ->assertOk();

        $this->assertSame(1, \App\Models\Category::count());
        $this->assertSame('food', getTranslatedContent(\App\Models\Category::first()->slug));
    }

    public function test_admin_category_destroy_responds(): void
    {
        $user = $this->seedUser();
        $category = $this->seedCategory('Travel');

        $this->actingAs($user)
            ->post('/admin/admin/categories/'.$category->id.'/delete')
            ->assertOk();
    }

    public function test_admin_routes_are_named_as_before(): void
    {
        $this->assertSame(url('/admin/dashboard'), route('admin.dashboard'));
        $this->assertSame(url('/admin/articles'), route('admin.article-index'));
        $this->assertSame(url('/admin/articles/create'), route('admin.article-create'));
        $this->assertSame(url('/admin/articles/store'), route('admin.article-store'));
        $this->assertSame(url('/admin/articles/1/edit'), route('admin.article-edit', ['id' => 1]));
        $this->assertSame(url('/admin/articles/1'), route('admin.article-update', ['id' => 1]));
        $this->assertSame(url('/admin/articles/1/delete'), route('admin.article-destroy', ['id' => 1]));
        $this->assertSame(url('/admin/admin/categories'), route('admin.category-index'));
        $this->assertSame(url('/admin/admin/categories/create'), route('admin.category-create'));
        $this->assertSame(url('/admin/admin/categories/store'), route('admin.category-store'));
        $this->assertSame(url('/admin/admin/categories/1/delete'), route('admin.category-destroy', ['id' => 1]));
    }
}
