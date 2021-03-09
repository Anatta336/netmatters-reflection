<?php declare(strict_types=1);
namespace Netmatters\Posts;

use Netmatters\Database\DatabaseInterface;

class PostsModel
{
    protected DatabaseInterface $database;

    function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function getRecentPostsArray(int $count): array
    {
        $sql = "SELECT post.slug AS slug, post.title AS title,
                    post.postedDate AS postedDate,
                    post.contentShort AS contentShort,
                    post.headImageUrl AS imageUrl,
                    category.name AS categoryName,
                    category.slug AS categorySlug,
                    postType.name AS typeName,
                    postType.slug AS typeSlug,
                    poster.name AS posterName,
                    poster.imageUrl AS posterImageUrl
                FROM post
                JOIN category ON post.categoryId = category.id
                JOIN postType ON post.typeId = postType.id
                JOIN poster ON post.posterId = poster.id
                ORDER BY postedDate DESC
                LIMIT ?";
        return $this->database->fetchResults($sql, $count);
    }

    
    /**
     * Retrieves the most recent posts.
     * @param int $count How many posts to fetch.
     * @return array Array of Post objects
     */
    public function getRecentPosts(int $count): array
    {
        return array_map(function ($postData) {
            return PostFactory::createFromArray($postData);
        }, $this->getRecentPostsArray($count));
    }
}

