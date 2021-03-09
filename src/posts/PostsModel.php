<?php declare(strict_types=1);
namespace Netmatters;

class PostsModel
{
    protected DatabaseInterface $database;

    function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function getRecentPosts(int $count): array
    {
        $sql = "SELECT post.slug AS slug, post.title AS title,
                    post.contentShort AS content, post.headImageUrl AS imageUrl,
                    category.name AS postCategory,
                    postType.name AS postType,
                    poster.name AS posterName,
                    poster.imageUrl AS posterImageUrl
                FROM post
                JOIN category ON post.categoryId = category.id
                JOIN postType ON post.typeId = postType.id
                JOIN poster ON post.posterId = poster.id
                ORDER BY post.postedDate DESC
                LIMIT ?";
        return $this->database->fetchResults($sql, $count);
    }
}

