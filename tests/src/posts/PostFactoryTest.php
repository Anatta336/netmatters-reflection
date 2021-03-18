<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\Image;
use Netmatters\Posts\Post;
use Netmatters\Posts\PostFactory;
use PHPUnit\Framework\TestCase;

class PostFactoryTest extends TestCase
{
    protected function createStubExtension(string $extension, string $pictureType): Extension
    {
        $stub = $this->createStub(Extension::class);
        $stub->method('getExtension')->willReturn($extension);
        $stub->method('getPictureType')->willReturn($pictureType);
        return $stub;
    }

    protected function createStubImage(int $id, string $imageUrl,
        array $extensions, Extension $defaultExtension): Image
    {
        $stub = $this->createStub(Image::class);
        $stub->method('getId')->willReturn($id);
        $stub->method('getImageUrl')->willReturn($imageUrl);
        $stub->method('getExtensions')->willReturn($extensions);
        $stub->method('getDefaultExtension')->willReturn($defaultExtension);
        return $stub;
    }

    public function testInstantiates(): void
    {
        $factory = new PostFactory();
        $this->assertInstanceOf(PostFactory::class, $factory);
    }

    public function testCreatesPostFromArray(): void
    {
        $jpg = $this->createStubExtension('jpg', 'image/jpeg');
        $headImage = $this->createStubImage(2, 'img/photos/gallery', [$jpg], $jpg);
        $posterImage = $this->createStubImage(1, 'img/avatars/netmatters', [$jpg], $jpg);

        $input = [
            'title' => 'Online Art Gallery Case Study',
            'slug' => 'online-art-gallery-case-study',
            'posted_date' => '2020-01-02',
            'category_name' => 'Web Design',
            'category_slug' => 'web-design',
            'post_type_name' => 'Case Studies',
            'post_type_slug' => 'case-studies',
            'poster_name' => 'Netmatters Ltd.',
            'content_short' => 'We made an online art gallery, and it was really successful',
        ];

        $factory = new PostFactory();
        $factory->createFromResults($input, $headImage, $posterImage);
        $post = $factory->createFromResults($input, $headImage, $posterImage);
        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame('Online Art Gallery Case Study', $post->getTitle());
        $this->assertSame('online-art-gallery-case-study', $post->getSlug());
        $this->assertEquals(new DateTime('2020-01-02', new DateTimeZone('UTC')), $post->getDate());
        $this->assertSame('Web Design', $post->getCategoryName());
        $this->assertSame('web-design', $post->getCategorySlug());
        $this->assertSame('Case Studies', $post->getTypeName());
        $this->assertSame('case-studies', $post->getTypeSlug());
        $this->assertSame('Netmatters Ltd.', $post->getPosterName());
        $this->assertSame('We made an online art gallery, and it was really successful', $post->getContentShort());
        $this->assertEquals($headImage, $post->getHeaderImage());
        $this->assertEquals($posterImage, $post->getPosterImage());
    }
}
