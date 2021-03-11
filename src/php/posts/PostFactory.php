<?php declare(strict_types=1);
namespace Netmatters\Posts;

use DateTime;
use Netmatters\Images\Image;

class PostFactory
{
    public function createFromResults(
        array $results,
        Image $headerImage,
        Image $posterImage): Post
    {
        return new Post(
            $results['title'],
            $results['slug'],
            new DateTime($results['postedDate']),
            $results['categoryName'],
            $results['categorySlug'],
            $results['typeName'],
            $results['typeSlug'],
            $results['posterName'],
            $posterImage,
            $results['contentShort'],
            $headerImage,
        );
    }
}
