<?php declare(strict_types=1);
namespace Netmatters\Posts;

use Netmatters\Database\DatabaseInterface;
use Netmatters\Images\ImageStore;
use Psr\Log\LoggerInterface;

/**
 * Handles fetching recent posts from a database.
 *
 * @package Post
 */
class PostStore
{
    /**
     * @var LoggerInterface A logger implementation.
     */
    protected LoggerInterface $logger;

    /**
     * @var DatabaseInterface The database to fetch from.
     */
    protected DatabaseInterface $database;

    /**
     * @var ImageStore An image store to use.
     */
    protected ImageStore $imageStore;

    /**
     * @var PostFactory A post factory to use.
     */
    protected PostFactory $postFactory;

    /**
     * @param LoggerInterface   $logger
     * @param DatabaseInterface $database
     * @param ImageStore        $imageStore
     * @param PostFactory       $postFactory
     */
    public function __construct(
        LoggerInterface $logger,
        DatabaseInterface $database,
        ImageStore $imageStore,
        PostFactory $postFactory
    ) {
        $this->logger      = $logger;
        $this->database    = $database;
        $this->imageStore  = $imageStore;
        $this->postFactory = $postFactory;
    }

    /**
     * Fetch an associative array representing recent posts.
     *
     * @param int $count How many posts to fetch
     *
     * @return array
     */
    public function getRecentPostsArray(int $count): array
    {
        $sql = "SELECT post.slug AS slug, post.title AS title,
                    post.posted_date AS posted_date,
                    post.content_short AS content_short,
                    post.image_id AS header_image_id,
                    category.name AS category_name,
                    category.slug AS category_slug,
                    post_type.name AS post_type_name,
                    post_type.slug AS post_type_slug,
                    poster.name AS poster_name,
                    poster.image_id AS poster_image_id
                FROM post
                JOIN category ON post.category_id = category.id
                JOIN post_type ON post.post_type_id = post_type.id
                JOIN poster ON post.poster_id = poster.id
                ORDER BY posted_date DESC
                LIMIT ?";
        return $this->database->fetchResults($sql, $count);
    }

    /**
     * Retrieves the most recent posts.
     *
     * @param int $count How many posts to fetch.
     *
     * @return Post[] The most recent posts, newest first.
     */
    public function getRecentPosts(int $count): array
    {
        $posts = [];
        foreach ($this->getRecentPostsArray($count) as $postData) {
            if (!isset($postData['header_image_id'])) {
                $this->logger->error("Missing header_image_id", [$postData]);
                continue;
            }
            $headerImage = $this->imageStore
                ->getImageById((int)$postData['header_image_id']);

            if (!isset($postData['poster_image_id'])) {
                $this->logger->error("Missing poster_image_id", [$postData]);
                continue;
            }
            $posterImage = $this->imageStore
                ->getImageById((int)$postData['poster_image_id']);

            $post = $this->postFactory->createFromResults(
                $postData,
                $headerImage,
                $posterImage
            );
            array_push($posts, $post);
        }

        return $posts;
    }
}
