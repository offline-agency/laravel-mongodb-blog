<?php

use Illuminate\Support\Facades\File;
use MongoDB\BSON\UTCDateTime;

if (!function_exists('formatDate')) {
    function formatDate(UTCDateTime $date)
    {

        $date = $date->toDateTime();

        return $date->format('j F Y'); // 28 april 2013 21:58:16
    }
}

if (!function_exists('getMedias')) {
    /**
     * @param $img_path
     * @return array
     */
    function getMedias($img_path)
    {
        $filesInFolder = File::files($img_path);

        $medias = [];
        foreach ($filesInFolder as $path) {
            $file = pathinfo($path);
            $medias [] = $file['basename'];
        }

        return $medias;
    }
}

if (!function_exists('processRelatedArticles')) {
    /**
     * @param $name
     * @param $request
     *
     * @return string
     */
    function processRelatedArticles($articles)
    {
        $arr = [];
        if ($articles != []) {
            foreach ($articles as $article) {

                $out = new stdClass;
                $out->ref_id = $article->ref_id;
                $out->title = getTranslatedContent($article->title);
                $out->excerpt = getTranslatedContent($article->excerpt);
                $out->content = getTranslatedContent($article->content);
                $out->publication_date = fixDate($article->publication_date);
                $out->autoincrement_id = $article->autoincrement_id;
                $out->slug = getTranslatedContent($article->slug);


                $arr[] = $out;
            }

            $arr = json_encode($arr);
        }
        return $arr;
    }

}

if (!function_exists('fixDate')) {


    function fixDate(UTCDateTime $DB_value)
    {

        if (!is_null($DB_value)) {
            $DB_value = $DB_value->__toString() / 1000;
            $DB_value = date('d-m-Y H:i:s', $DB_value);//DB date
            return $DB_value;
        } else {
            return null;
        }

    }
}
