<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\User;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

class SeedersTest extends TestCase
{
    public function test_database_seeder_creates_the_demo_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(1, User::count());
        $this->assertSame(10, Category::count());
        $this->assertSame(50, Article::count());
    }

    public function test_seeded_user_can_be_found_by_email(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame('John', User::where('email', 'test@test.com')->first()->name);
    }
}
