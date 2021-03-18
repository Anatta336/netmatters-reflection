<?php declare(strict_types=1);

use Netmatters\Posts\Post;
use Netmatters\Posts\PostsView;
use Netmatters\Images\Image;
use Netmatters\Images\Extensions\Extension;
use PHPUnit\Framework\TestCase;

class PostsViewTest extends TestCase
{
    protected function createStubPost(
        $title,
        $slug,
        $date,
        $categoryName,
        $categorySlug,
        $typeName,
        $typeSlug,
        $posterName,
        $posterImage,
        $contentShort,
        $headerImage,
    ): Post
    {
        $stub = $this->createStub(Post::class);
        $stub->method('getTitle')->willReturn($title);
        $stub->method('getSlug')->willReturn($slug);
        $stub->method('getDate')->willReturn($date);
        $stub->method('getCategoryName')->willReturn($categoryName);
        $stub->method('getCategorySlug')->willReturn($categorySlug);
        $stub->method('getTypeName')->willReturn($typeName);
        $stub->method('getTypeSlug')->willReturn($typeSlug);
        $stub->method('getPosterName')->willReturn($posterName);
        $stub->method('getPosterImage')->willReturn($posterImage);
        $stub->method('getContentShort')->willReturn($contentShort);
        $stub->method('getHeaderImage')->willReturn($headerImage);
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

    protected function createStubExtension(string $extension, string $pictureType): Extension
    {
        $stub = $this->createStub(Extension::class);
        $stub->method('getExtension')->willReturn($extension);
        $stub->method('getPictureType')->willReturn($pictureType);
        return $stub;
    }

    public function testInstantiates(): void
    {
        $view = new PostsView();
        $this->assertInstanceOf(PostsView::class, $view);
    }

    public function testStoreArticleUrl(): void
    {
        $view = new PostsView();
        $view->setArticleUrlStart('test/something/more');
        $this->assertSame('test/something/more', $view->getArticleUrlStart());
    }

    public function testStoreCategoryUrl(): void
    {
        $view = new PostsView();
        $view->setCategoryUrlStart('test/something/more');
        $this->assertSame('test/something/more', $view->getCategoryUrlStart());
    }

    public function testStoreImageUrl(): void
    {
        $view = new PostsView();
        $view->setImageUrlStart('test/something/more');
        $this->assertSame('test/something/more', $view->getImageUrlStart());
    }

    public function testGenerateHtmlForAPost(): void
    {
        $jpg = $this->createStubExtension('jpg', 'image/jpeg');
        $userImage = $this->createStubImage(
            1,
            'img/users/netmatters-logo',
            [$jpg],
            $jpg,
        );
        $imageOne = $this->createStubImage(
            2,
            'img/case-studies/gallery-header',
            [$jpg],
            $jpg,
        );
        $postOne = $this->createStubPost(
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

        $expected = <<<'EOT'
        <section class="latest-posts">
        <div class="heading-wrapper">
        <h2>Latest</h2>
        </div>
        <div class="wrapper">
        <div class="content">
        <article class="it-support">
        <a class="category" href="page/case-studies/it-support" title="View all: IT Support / Case Studies">
        Case Studies
        <div class="tooltip-wrapper"><div class="tooltip-content"><p>View all: IT Support / Case Studies</p>
        </div>
        </div>
        </a>
        <a class="lead" href="page/online-art-gallery-case-study">
        <picture>
        <source srcset="img/case-studies/gallery-header.jpg" type="image/jpeg">
        <img src="img/case-studies/gallery-header.jpg" alt="Online Art Gallery Case Study">
        </picture>
        </a>
        <a href="page/online-art-gallery-case-study">
        <h3>Online Art Gallery Case Study</h3>
        </a>
        <p>We made an online art gallery, and it was really successful&#8230;</p>
        <a class="button" href="page/online-art-gallery-case-study" aria-label="Read more">
        Read More
        </a>
        <div class="poster">
        <picture>
        <source srcset="img/users/netmatters-logo.jpg" type="image/jpeg">
        <img src="img/users/netmatters-logo.jpg" alt="Netmatters Ltd.">
        </picture>
        <p>Posted by Netmatters Ltd.</p>
        <p class="date">2nd January 2020</p>
        </div>
        </article>
        </div>
        </div>
        </section>

        EOT;

        $view = new PostsView();
        $this->assertSame($expected, $view->htmlLatestPosts([$postOne]));
    }

}
