<?php declare(strict_types=1);
namespace Netmatters\Posts;

use Netmatters\Database\DatabaseInterface;
use Netmatters\Images\ImagesModel;

class PostsModel
{
    protected DatabaseInterface $database;
    protected ImagesModel $imagesModel;
    protected PostFactory $postFactory;

    function __construct(
        DatabaseInterface $database,
        ImagesModel $imagesModel,
        PostFactory $postFactory)
    {
        $this->database = $database;
        $this->imagesModel = $imagesModel;
        $this->postFactory = $postFactory;
    }

    public function getRecentPostsArray(int $count): array
    {
        $sql = "SELECT post.slug AS slug, post.title AS title,
                    post.postedDate AS postedDate,
                    post.contentShort AS contentShort,
                    post.headImageId AS headerImageId,
                    category.name AS categoryName,
                    category.slug AS categorySlug,
                    postType.name AS typeName,
                    postType.slug AS typeSlug,
                    poster.name AS posterName,
                    poster.imageId AS posterImageId
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
        $posts = [];
        foreach ($this->getRecentPostsArray($count) as $postData) {
            if (!isset($postData['headerImageId'])) {
                // TODO: log warning
                echo "no header image id";
                continue;
            }
            $headerImage = $this->imagesModel
                ->getImageById((int)$postData['headerImageId']);

            if (!isset($postData['posterImageId'])) {
                // TODO: log warning
                echo "no poster image id";
                continue;
            }
            $posterImage = $this->imagesModel
                ->getImageById((int)$postData['posterImageId']);

            $post = $this->postFactory->createFromResults(
                $postData, $headerImage, $posterImage);
            array_push($posts, $post);
        }

        return $posts;
    }
}

