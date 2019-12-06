<?php


use App\Models\Category;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoriesTableSeeder  extends Seeder
{

    public function run()
    {
        Carbon::setLocale( cl());
        $categories_number = 10;

        $img_path = public_path('images/blog/');

        $medias = getMedias($img_path);

        for ($i = 0; $i <= $categories_number-1; $i++) {
            $faker = Factory::create( cl() );

            $arr = [];
            $category = new Category;

            $arr['name'] = ucfirst($faker->word());
            $arr['slug'] =  Str::slug($arr['name']);
            $arr['description'] = $faker->paragraph();
            $arr['media_path'] = $faker->randomElement( $medias );
            $arr['articles'] = json_encode([]);

            $request = new Request;
            $category->storeWithSync($request,$arr);

            gc_collect_cycles(); //Required to optmize Faker performance
        }
    }
}
