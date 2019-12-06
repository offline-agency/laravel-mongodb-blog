<?php

namespace App\Models;


use Jenssegers\Mongodb\Helpers\EloquentBuilder;
use OfflineAgency\MongoAutoSync\Http\Models\MDModel;

/**
 *
 * Plain Fields
 *
 * @property string $id
 * @property array $title
 * @property array $excerpt
 * @property array $content
 * @property string $autoincrement_id
 * @property string $publication_date
 * @property array $slug
 *
 **/


/**
 *
 * Relationship
 *
 * @property MiniCategory $categories
 *
 **/

/**
 * Article
 *
 * @mixin EloquentBuilder
 */

class Article extends MDModel
{
    protected $collection = 'articles';


    protected $items = [
        'title' => [
            'is-ml' => true,
            'is-editable' => true,
        ],
        'excerpt' => [
            'is-ml' => true,
        ],
        'content' => [
            'is-ml' => true,
        ],
        'autoincrement_id' => [
            'is-editable' => false,
        ],
        'publication_date' => [
            'is-md' => true,
        ],
        'slug' => [
            'is-ml' => true,
        ],
        'author' => [],
        'media_path' => [],

    ];

    protected $mongoRelation = array(
        'categories' => array(
            'type' => 'EmbedsMany',
            'mode' => 'classic',
            'model' => 'App\Models\MiniCategory',
            'modelTarget' => 'App\Models\Category',
            'methodOnTarget' => 'articles',
            'modelOnTarget' => 'App\Models\MiniArticle',
        ),
    );


    public function categories()
    {
        return $this->embedsMany('App\Models\MiniCategory');
    }

}
