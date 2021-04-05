<?php declare(strict_types=1);
namespace Netmatters\Posts;

use DateTime;
use DateTimeZone;
use Netmatters\Images\Image;

/**
 * Builds a Post object.
 *
 * @package Post
 */
class PostFactory
{
    /**
     * Builds a Post object from an associative array, of the
     * sort given by DatabaseInterface's fetchResults method.
     *
     * @param array $results     Associative array of data for one post.
     * @param Image $headerImage Image to use for post.
     * @param Image $posterImage Image to use for post creator.
     *
     * @return Post
     */
    public function createFromResults(
        array $results,
        Image $headerImage,
        Image $posterImage
    ): Post {
        return new Post(
            $results['title'],
            $results['slug'],
            new DateTime($results['posted_date'], new DateTimeZone('UTC')),
            $results['category_name'],
            $results['category_slug'],
            $results['post_type_name'],
            $results['post_type_slug'],
            $results['poster_name'],
            $posterImage,
            $results['content_short'],
            $headerImage,
        );
    }
}
