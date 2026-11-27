<?php

namespace Tests\Feature;

use App\Models\Category;
use Tests\Concerns\SeedsBlog;
use Tests\TestCase;

class DropCollectionCommandTest extends TestCase
{
    use SeedsBlog;

    public function test_it_destroys_every_row_of_the_collection(): void
    {
        $this->seedCategory('Travel');
        $this->seedCategory('Food');

        $this->artisan('drop:collection', ['collection_name' => 'category'])->assertSuccessful();

        $this->assertSame(0, Category::count());
    }

    public function test_it_warns_when_the_collection_is_empty(): void
    {
        $this->artisan('drop:collection', ['collection_name' => 'category'])
            ->expectsOutputToContain('No record found on collection category')
            ->assertSuccessful();
    }
}
