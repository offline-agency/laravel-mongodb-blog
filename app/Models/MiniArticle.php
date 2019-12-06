<?php


namespace App\Models;


use OfflineAgency\MongoAutoSync\Http\Models\DefaultMini;

/**
 *
 * Plain Fields
 *
 * @property string $id
 * @property string $ref_id
 * @property array $title
 * @property array $excerption
 * @property array $slug
 * @property array $content
 * @property string $status
 * @property string $source_label
 * @property string $source_link
 * @property string $img_credit_temp
 * @property string $img_evidence_text
 *
 **/
class MiniArticle extends DefaultMini
{
    protected $items = array(
        'ref_id' => array(),
        'title' => array(
            'is-ml' => true,
            'is-editable' => true,
        ),
        'excerpt' => array(
            'is-ml' => true,
        ),
        'content' => array(
            'is-ml' => true,
        ),
        'autoincrement_id' => array(
            'is-editable' => false,
        ),
        'publication_date' => array(
            'is-md' => true,
        ),
        'slug' => array(
            'is-ml' => true,
        ),
    );
}
