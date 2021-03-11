<?php declare(strict_types=1);
/*
use Netmatters\Database\DatabaseInterface;
use Netmatters\Posts\PostsModel;
use PHPUnit\Framework\TestCase;

class PostsModelTest extends TestCase
{
    private $data = [
        ['name' => 'Joe', 'balance' => 12.34],
        ['name' => 'Alice', 'balance' => 45.67],
        ['name' => 'Ahmed', 'balance' => 314.57],
        ['name' => 'Bethany', 'balance' => -2.34],
    ];

    // this string must match what is passed by PostsModel when it calls fetchResults on DatabaseInterface,
    // including white space!
    private $sql = "SELECT post.slug AS slug, post.title AS title,
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
    private $databaseStub;

    protected function setUp(): void
    {
        $this->databaseStub = $this->createStub(DatabaseInterface::class);
        $this->databaseStub->method('fetchResults')
            ->willReturnMap([
                [$this->sql, 1, [$this->data[3]]],
                [$this->sql, 2, [$this->data[3], $this->data[0]]],
                [$this->sql, 3, [$this->data[3], $this->data[0], $this->data[1]]],
            ]);
    }

    public function testInstantiates(): void
    {
        $model = new PostsModel($this->databaseStub);
        $this->assertInstanceOf(PostsModel::class, $model);
    }

    public function testReturnsOneResult(): void
    {
        $model = new PostsModel($this->databaseStub);
        $recentPosts = $model->getRecentPostsArray(1);
        $this->assertSame([$this->data[3]], $recentPosts);
    }

    public function testReturnsTwoResults(): void
    {
        $model = new PostsModel($this->databaseStub);
        $recentPosts = $model->getRecentPostsArray(2);
        $this->assertSame([$this->data[3], $this->data[0]], $recentPosts);
    }

    public function testReturnsThreeResults(): void
    {
        $model = new PostsModel($this->databaseStub);
        $recentPosts = $model->getRecentPostsArray(3);
        $this->assertSame([$this->data[3], $this->data[0], $this->data[1]], $recentPosts);
    }
}
*/
