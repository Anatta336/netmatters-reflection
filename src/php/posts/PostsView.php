<?php declare(strict_types=1);
namespace Netmatters\Posts;

use Netmatters\Images\ImageView;

class PostsView
{
    protected string $categoryUrlStart = 'page/';
    protected string $articleUrlStart = 'page/';
    protected string $imageUrlStart = '';
    protected array $posts;

    /**
     * @param array Array of Post objects. The posts that this will display.
     */
    function __construct(array $posts)
    {
        $this->posts = $posts;
    }

    public function getCategoryUrlStart(): string
    {
        return $this->categoryUrlStart;
    }
    public function setCategoryUrlStart(string $value): string
    {
        $this->categoryUrlStart = htmlspecialchars($value, ENT_QUOTES);
        return $this->categoryUrlStart;
    }

    public function getArticleUrlStart(): string
    {
        return $this->articleUrlStart;
    }
    public function setArticleUrlStart(string $value): string
    {
        $this->articleUrlStart = htmlspecialchars($value, ENT_QUOTES);
        return $this->articleUrlStart;
    }

    public function getImageUrlStart(): string
    {
        return $this->imageUrlStart;
    }
    public function setImageUrlStart(string $value): string
    {
        $this->imageUrlStart = htmlspecialchars($value, ENT_QUOTES);
        return $this->imageUrlStart;
    }

    public function htmlLatestPosts(): string
    {
        /** @var ImageView $imageView */
        $imageView = new ImageView();

        /** @var string $result */
        $result = <<<"EOT"
        <section class="latest-posts">
            <div class="heading-wrapper">
                <h2>Latest</h2>
            </div>
            <div class="wrapper">
            <div class="content">

        EOT;

        /** @var Post $post */
        foreach ($this->posts as $post) {
            $result .= <<<"EOT"
            <article class="{$post->getCategorySlug()}">
                <a class="category" href="{$this->getCategoryUrlStart()}{$post->getTypeSlug()}/{$post->getCategorySlug()}" title="View all: {$post->getCategoryName()} / {$post->getTypeName()}">
                    {$post->getTypeName()}
                    <div class="tooltip-wrapper">
                    <div class="tooltip-content">
                        <p>View all: {$post->getCategoryName()} / {$post->getTypeName()}</p>
                    </div>
                    </div>
                </a>
                <a class="lead" href="{$this->getArticleUrlStart()}{$post->getSlug()}">
                    {$imageView->pictureHtml($post->getHeaderImage(), $this->getImageUrlStart(), $post->getTitle())}
                </a>
                <a href="{$this->getArticleUrlStart()}{$post->getSlug()}">
                    <h3>{$post->getTitle()}"</h3>
                </a>
                <p>{$post->getContentShort()}&#8230</p>
                <a class="button" href="{$this->getArticleUrlStart()}{$post->getSlug()}" aria-label="Read more">Read More</a>
                <div class="poster">
                    {$imageView->pictureHtml($post->getPosterImage(), $this->getImageUrlStart(), $post->getPosterName())}
                    <p>Posted by {$post->getPosterName()}</p>
                    <p class="date">{$post->getDate()->format('jS F Y')}</p>
                </div>
            </article>

            EOT;
        }

        $result .= <<<"EOT"
            </div>
            </div>
        </section>

        EOT;
        return $result;
    }
}
