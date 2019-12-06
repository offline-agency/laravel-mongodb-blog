<?php


use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;
use Faker\Factory;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ArticlesTableSeeder  extends Seeder
{
    public function run()
    {

        Carbon::setLocale( cl());
        $articleNumber = 50;
        $authorNumber = 10;
        $img_path = public_path('images/blog/');

        $authors = $this->getAuthors($authorNumber);
        $medias = getMedias($img_path);
        $categories = $this->getCategories();

        for ($i = 0; $i <= $articleNumber-1; $i++) {
            $faker = Factory::create( cl() );
            $publication_date = Carbon::parse( $faker->dateTimeBetween( $startDate = '-100 days', $endDate = 'now' ))->toDateTimeString();
            $rand_numb_cat =  $faker->numberBetween(2,4);

            $arr = [];
            $article = new Article;

            $arr['title'] = $faker->sentence();
            $arr['excerpt'] = $faker->paragraph();
            $arr['content'] = $faker->realText(2000);
            $arr['autoincrement_id'] = getAID( $article );
            $arr['slug'] = Str::slug($arr['title']) . "-" .  $arr['autoincrement_id'];
            $arr['publication_date'] = $publication_date;
            $arr['author'] = $faker->randomElement( $authors );
            $arr['media_path'] = $faker->randomElement( $medias );
            $arr['categories'] = json_encode($faker->randomElements( $categories, $rand_numb_cat ));

            $request = new Request;
            $article->storeWithSync($request,$arr);

            gc_collect_cycles(); //Required to optmize Faker performance
        }

    }

    /**
     * @param int $authorNumber
     * @return array
     */
    private function getAuthors(int $authorNumber)
    {
        $faker = Faker\Factory::create(cl()); // create a faker
        $authors = [];

        for ($i = 0; $i < 10; $i++) {
            $authors[] = $faker->name;
        }
        return $authors;
    }

    private function getCategories()
    {
        $categories = new Category;
        $categories = $categories->all();

        return $this->formatMiniCategories($categories);
    }

    private function formatMiniCategories(Collection $categories)
    {
        $miniCategories = [];
        foreach ($categories as $category){

            $miniCategory = new StdClass();

            $miniCategory->ref_id = $category->id;
            $miniCategory->name = getTranslatedContent($category->name);
            $miniCategory->slug = getTranslatedContent($category->slug);
            $miniCategory->description = getTranslatedContent($category->description);
            $miniCategory->img_path = $category->img_path;


            $miniCategories[] = $miniCategory;
        }

        return $miniCategories;
    }

}
