<?php

namespace App\Models;

use Jenssegers\Mongodb\Helpers\EloquentBuilder;
use OfflineAgency\MongoAutoSync\Http\Models\MDModel;


/**
 *
 * Plain Fields
 *
 * @property string $id
 * @property array $name
 * @property array $slug
 * @property array $description
 *
 **/




/**
 *
 * Relationship
 *
 * @property MiniArticle $articlelist
 *
 **/



/**
 * Category
 *
 * @mixin EloquentBuilder
 */

class Category extends MDModel
{
    protected $collection = 'categories';

    protected $items = [
        'name'        => [
            'is-ml' => true,
        ],
        'slug'       => [
            'is-ml' => true,
        ],
        'description' => [
            'is-ml' => true,
        ],
        'media_path' => [],

    ];

    protected $mongoRelation = array(
        'articles' => array(
            'type'   => 'EmbedsMany',
            'mode' => 'classic',
            'model' => 'App\Models\MiniArticle',
            'modelTarget' => 'App\Models\Article',
            'methodOnTarget' => 'categories',
            'modelOnTarget' => 'App\Models\MiniCategory',
        ),
    );

    public function articles()
    {
        return $this->embedsMany('App\Models\MiniArticle');
    }
}
