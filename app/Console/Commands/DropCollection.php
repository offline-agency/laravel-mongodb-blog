<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use OfflineAgency\MongoAutoSync\Http\Models\MDModel;

class DropCollection extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drop:collection {collection_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all elements of the collection given as input';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $collection_name = $this->argument( 'collection_name' );

        $model = $this->getModelPathByName($collection_name);

        if(!is_null($model)){

            $model = $model->all();

            $count = $model->count();
            $bar   = $this->output->createProgressBar( $count );

            if($count > 0){
                for ( $i = 0; $i <= $count-1; $i ++ ) {
                    $bar->advance();
                    $this->line(' Destroy row #' . ($i+1));
                    $model[$i]->destroyWithSync();
                }
            }else{
                $this->warn('No record found on collection ' . strtolower($collection_name));
            }
        }else{
            $this->error('Error Model not found \n');
        }

    }


    /**
     * @param string|null $collection_name
     *
     * @return MDModel
     */
    private function getModelPathByName(?string $collection_name)
    {
        $path = app_path() . "/Models";

        $modelPath = $this->searchOnModels($path, $collection_name);

        return new $modelPath;
    }

    private function searchOnModels(string $path, ?string $collection_name)
    {
        $out = "";

        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') {
                continue;
            }
            $filename = $path . '/' . $result;
            if (is_dir($filename)) {
                $out = $this->searchOnModels($filename, $collection_name);
            } else if (strtolower(substr($result, 0, -4)) == strtolower($collection_name)) {
                return 'App\Models\\' . substr($result, 0, -4);
            }
        }

        if (strtolower($collection_name) == "user") {
            $out = 'App\User';
        }

        return $out;
    }
}
