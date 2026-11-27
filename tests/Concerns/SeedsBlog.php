<?php

namespace Tests\Concerns;

use App\Models\Article;
use App\Models\Category;
use App\User;
use Carbon\Carbon;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait SeedsBlog
{
    /**
     * The demo user of the seeder (test@test.com / demo).
     */
    protected function seedUser(): User
    {
        $this->seed(UsersTableSeeder::class);

        return User::where('email', 'test@test.com')->firstOrFail();
    }

    /**
     * Create a category the same way the seeders do.
     */
    protected function seedCategory(string $name = 'Travel'): Category
    {
        $category = new Category;

        $category->storeWithSync(new Request, [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => 'About '.$name,
            'media_path' => 'dog.jpeg',
            'articles' => json_encode([]),
        ]);

        return Category::where('slug.'.cl(), Str::slug($name))->firstOrFail();
    }

    /**
     * Create an article linked to the given category the same way the seeders do.
     */
    protected function seedArticle(Category $category, string $title = 'Hello Mongo'): Article
    {
        $article = new Article;
        $autoincrementId = getAID($article);

        $mini = new \stdClass;
        $mini->ref_id = $category->id;
        $mini->name = getTranslatedContent($category->name);
        $mini->slug = getTranslatedContent($category->slug);
        $mini->description = getTranslatedContent($category->description);
        $mini->img_path = $category->img_path;

        $article->storeWithSync(new Request, [
            'title' => $title,
            'excerpt' => 'Excerpt of '.$title,
            'content' => 'Content of '.$title,
            'autoincrement_id' => $autoincrementId,
            'slug' => Str::slug($title).'-'.$autoincrementId,
            'publication_date' => Carbon::parse('2026-03-14 10:00:00')->toDateTimeString(),
            'author' => 'Jane Doe',
            'media_path' => 'dog.jpeg',
            'categories' => json_encode([$mini]),
        ]);

        return Article::where('slug.'.cl(), Str::slug($title).'-'.$autoincrementId)->firstOrFail();
    }

    protected function articleSlug(Article $article): string
    {
        return getTranslatedContent($article->slug);
    }

    protected function categorySlug(Category $category): string
    {
        return getTranslatedContent($category->slug);
    }
}
