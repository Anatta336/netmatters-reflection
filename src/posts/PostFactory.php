<?php declare(strict_types=1);
namespace Netmatters\Posts;

use DateTime;

class PostFactory
{
    public static function createFromArray(array $array): Post
    {
        return new Post(
            $array['title'],
            $array['slug'],
            new DateTime($array['postedDate']),
            $array['categoryName'],
            $array['categorySlug'],
            $array['typeName'],
            $array['typeSlug'],
            $array['posterName'],
            $array['posterImageUrl'],
            $array['contentShort'],
            $array['imageUrl']
        );
    }
}
