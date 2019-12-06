<?php


namespace App\Models;

use OfflineAgency\MongoAutoSync\Http\Models\DefaultMini;

/**
 *
 * Plain Fields
 *
 * @property string $id
 * @property string $ref_id
 * @property array $name
 * @property array $slug
 * @property string $colour
 *
 **/

class MiniCategory extends DefaultMini {


    protected $items = array(
        'ref_id'       => [],
        'name'        => [
            'is-ml' => true,
        ],
        'slug' => [
            'is-ml' => true,
        ],
        'description' => [
            'is-ml' => true,
        ],
        'img_path' => []
    );
}

