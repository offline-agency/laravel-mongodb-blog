<?php

namespace Tests\Feature;

use Tests\Concerns\SeedsBlog;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use SeedsBlog;

    public function test_home_lists_the_latest_articles(): void
    {
        $category = $this->seedCategory('Travel');
        $this->seedArticle($category, 'Hello Mongo');

        $this->get('/')
            ->assertOk()
            ->assertSee('Hello Mongo')
            ->assertSee('14 March 2026');
    }

    public function test_home_renders_without_articles(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_category_list_shows_every_category(): void
    {
        $this->seedCategory('Travel');
        $this->seedCategory('Food');

        $this->get('/category')
            ->assertOk()
            ->assertSee('Travel')
            ->assertSee('Food');
    }

    public function test_article_detail_shows_the_article(): void
    {
        $article = $this->seedArticle($this->seedCategory('Travel'), 'Hello Mongo');

        $this->get('/'.$this->articleSlug($article))
            ->assertOk()
            ->assertSee('Hello Mongo')
            ->assertSee('Jane Doe');
    }

    public function test_unknown_article_slug_returns_404(): void
    {
        $this->get('/not-an-article')->assertNotFound();
    }

    public function test_category_detail_lists_the_articles_of_the_category(): void
    {
        $category = $this->seedCategory('Travel');
        $this->seedArticle($category, 'Hello Mongo');

        $this->get('/category/'.$this->categorySlug($category))
            ->assertOk()
            ->assertSee('Hello Mongo');
    }

    public function test_routes_are_named_as_before(): void
    {
        $this->assertSame(url('/'), route('blog'));
        $this->assertSame(url('/category'), route('category-list'));
        $this->assertSame(url('/some-slug'), route('article-detail', ['some-slug']));
        $this->assertSame(url('/category/some-slug'), route('category-detail', ['some-slug']));
    }
}
