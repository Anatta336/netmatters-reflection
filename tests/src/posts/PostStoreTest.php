<?php declare(strict_types=1);

use Netmatters\Database\DatabaseInterface;
use Netmatters\Posts\Post;
use Netmatters\Images\Image;
use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\ImageStore;
use Netmatters\Posts\PostFactory;
use Netmatters\Posts\PostStore;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PostStoreTest extends TestCase
{
    protected function createStubLogger(): LoggerInterface
    {
        $stub = $this->createStub(LoggerInterface::class);
        return $stub;
    }

    protected function createStubDatabase(): DatabaseInterface
    {
        $stub = $this->createStub(DatabaseInterface::class);
        $stub->method('fetchResults')->willReturn(['posts', 'would', 'be', 'here']);
        $stub->method('runQuery')->willReturn(true);
        return $stub;
    }

    protected function createPartialStubImageStore(): ImageStore
    {
        $stub = $this->createStub(ImageStore::class);
        return $stub;
    }

    protected function createUserImage(): Image
    {
        $jpg = new Extension(1, 'jpg', 'image/jpeg');
        return new Image(
            1,
            'img/users/netmatters-logo',
            [$jpg],
            $jpg,
        );
    }

    protected function createGalleryHeadImage(): Image
    {
        $jpg = new Extension(1, 'jpg', 'image/jpeg');
        return new Image(
            2,
            'img/case-studies/gallery-header',
            [$jpg],
            $jpg,
        );
    }

    protected function createGalleryPost(): Post
    {
        $userImage = $this->createUserImage();
        $imageOne = $this->createGalleryHeadImage();
        $post = new Post(
            'Online Art Gallery Case Study',
            'online-art-gallery-case-study',
            new DateTime('2020-01-02'),
            'IT Support',
            'it-support',
            'Case Studies',
            'case-studies',
            'Netmatters Ltd.',
            $userImage,
            'We made an online art gallery, and it was really successful',
            $imageOne,
        );
        return $post;
    }

    protected function createPartialStubPostFactory(): PostFactory
    {
        $post = $this->createGalleryPost();
        $stub = $this->createStub(PostFactory::class);
        $stub->method('createFromResults')->willReturn($post);
        return $stub;
    }

    public function testInstantiates(): void
    {
        $logger = $this->createStubLogger();
        $database = $this->createStubDatabase();
        $imageStore = $this->createPartialStubImageStore();
        $postFactory = $this->createPartialStubPostFactory();
        $postStore = new PostStore($logger, $database, $imageStore, $postFactory);
        $this->assertInstanceOf(PostStore::class, $postStore);
    }

    public function testGetRecentPostsAssociativeArray(): void
    {
        $logger = $this->createStubLogger();
        $database = $this->createStubDatabase();
        $imageStore = $this->createPartialStubImageStore();
        $postFactory = $this->createPartialStubPostFactory();
        $postStore = new PostStore($logger, $database, $imageStore, $postFactory);
        $this->assertSame(['posts', 'would', 'be', 'here'], $postStore->getRecentPostsArray(4));
    }

    public function testGetRecentPosts(): void
    {
        $database = $this->createStub(DatabaseInterface::class);
        $database->method('fetchResults')->willReturn([
            [
                'title' => 'Online Art Gallery Case Study',
                'slug' => 'online-art-gallery-case-study',
                'posted_date' => '2020-01-02',
                'header_image_id' => 2,
                'category_name' => 'Web Design',
                'category_slug' => 'web-design',
                'post_type_name' => 'Case Studies',
                'post_type_slug' => 'case-studies',
                'poster_name' => 'Netmatters Ltd.',
                'poster_image_id' => 1,
                'content_short' => 'We made an online art gallery, and it was really successful',
            ]
        ]);

        $userImage = $this->createUserImage();
        $galleryImage = $this->createGalleryHeadImage();

        $imageStore = $this->createStub(ImageStore::class);
        $imageStore->method('getImageById')->will($this->returnValueMap([
            [1, $userImage],
            [2, $galleryImage],
        ]));
        
        $logger = $this->createStubLogger();
        $postFactory = $this->createPartialStubPostFactory();
        $postStore = new PostStore($logger, $database, $imageStore, $postFactory);
        $this->assertEquals([$this->createGalleryPost()], $postStore->getRecentPosts(1));
    }

    public function testRecentPostEmptyIfNoHeaderImageId(): void
    {
        $database = $this->createStub(DatabaseInterface::class);
        $database->method('fetchResults')->willReturn([
            [
                'title' => 'Online Art Gallery Case Study',
                'slug' => 'online-art-gallery-case-study',
                'posted_date' => '2020-01-02',
                // 'header_image_id' => 2,
                'category_name' => 'Web Design',
                'category_slug' => 'web-design',
                'post_type_name' => 'Case Studies',
                'post_type_slug' => 'case-studies',
                'poster_name' => 'Netmatters Ltd.',
                'poster_image_id' => 1,
                'content_short' => 'We made an online art gallery, and it was really successful',
            ]
        ]);

        $userImage = $this->createUserImage();
        $galleryImage = $this->createGalleryHeadImage();

        $imageStore = $this->createStub(ImageStore::class);
        $imageStore->method('getImageById')->will($this->returnValueMap([
            [1, $userImage],
            [2, $galleryImage],
        ]));
        
        $logger = $this->createStubLogger();
        $postFactory = $this->createPartialStubPostFactory();
        $postStore = new PostStore($logger, $database, $imageStore, $postFactory);
        $this->assertEquals([], $postStore->getRecentPosts(1));
    }

    public function testRecentPostEmptyIfNoPosterImageId(): void
    {
        $database = $this->createStub(DatabaseInterface::class);
        $database->method('fetchResults')->willReturn([
            [
                'title' => 'Online Art Gallery Case Study',
                'slug' => 'online-art-gallery-case-study',
                'posted_date' => '2020-01-02',
                'header_image_id' => 2,
                'category_name' => 'Web Design',
                'category_slug' => 'web-design',
                'post_type_name' => 'Case Studies',
                'post_type_slug' => 'case-studies',
                'poster_name' => 'Netmatters Ltd.',
                // 'poster_image_id' => 1,
                'content_short' => 'We made an online art gallery, and it was really successful',
            ]
        ]);

        $userImage = $this->createUserImage();
        $galleryImage = $this->createGalleryHeadImage();

        $imageStore = $this->createStub(ImageStore::class);
        $imageStore->method('getImageById')->will($this->returnValueMap([
            [1, $userImage],
            [2, $galleryImage],
        ]));
        
        $logger = $this->createStubLogger();
        $postFactory = $this->createPartialStubPostFactory();
        $postStore = new PostStore($logger, $database, $imageStore, $postFactory);
        $this->assertEquals([], $postStore->getRecentPosts(1));
    }
}
