<?php declare(strict_types=1);
namespace Netmatters\Posts;

use Netmatters\Images\ImageView;

/**
 * Creates the HTML for the "latest posts" section, from an
 * array of Post objects.
 *
 * @package Post
 */
class PostsView
{
    /**
     * Path to be prefixed on every category slug.
     *
     * @var string
     */
    protected string $categoryUrlStart = 'page/';

    /**
     * Path to be prefixed on every article slug.
     *
     * @var string
     */
    protected string $articleUrlStart = 'page/';

    /**
     * Path to be prefixed on every image url.
     *
     * @var string
     */
    protected string $imageUrlStart = '';

    /**
     * The posts that will have their details displayed.
     *
     * @var Post[]
     */
    protected array $posts;

    /**
     * @param Post[] $posts Array of Post objects, which will be represented in HTML.
     */
    public function __construct(array $posts)
    {
        $this->posts = $posts;
    }

    /**
     * Get the path to be prefixed on every category slug.
     *
     * @return string
     */
    public function getCategoryUrlStart(): string
    {
        return $this->categoryUrlStart;
    }

    /**
     * Set the path to be prefixed on every category slug.
     * The string has HTML special characters escaped before being stored.
     *
     * @param string $value
     *
     * @return string The value that was stored.
     */
    public function setCategoryUrlStart(string $value): string
    {
        $this->categoryUrlStart = htmlspecialchars($value, ENT_QUOTES);
        return $this->categoryUrlStart;
    }

    /**
     * Get the path to be prefixed on every article slug.
     *
     * @return string
     */
    public function getArticleUrlStart(): string
    {
        return $this->articleUrlStart;
    }

    /**
     * Set the path to be prefixed on every article slug.
     * The string has HTML special characters escaped before being stored.
     *
     * @param string $value
     *
     * @return string The value that was stored.
     */
    public function setArticleUrlStart(string $value): string
    {
        $this->articleUrlStart = htmlspecialchars($value, ENT_QUOTES);
        return $this->articleUrlStart;
    }

    /**
     * Get the path to be prefixed on every image url.
     *
     * @return string
     */
    public function getImageUrlStart(): string
    {
        return $this->imageUrlStart;
    }

    /**
     * Set the path to be prefixed on every image url.
     * HTML special characters will be escaped.
     *
     * @param string $value
     *
     * @return string The value that was stored.
     */
    public function setImageUrlStart(string $value): string
    {
        $this->imageUrlStart = htmlspecialchars($value, ENT_QUOTES);
        return $this->imageUrlStart;
    }

    /**
     * Generate HTML for a "latest-posts" section that previews the
     * posts passed into this object on construction.
     *
     * @return string A string containing valid HTML.
     */
    public function htmlLatestPosts(): string
    {
        /**
         * @var ImageView $imageView
         */
        $imageView = new ImageView();

        /**
         * @var string $result
         */
        $result = <<<"EOT"
        <section class="latest-posts">
            <div class="heading-wrapper">
                <h2>Latest</h2>
            </div>
            <div class="wrapper">
            <div class="content">

        EOT;

        /**
         * @var Post $post
         * */
        foreach ($this->posts as $post) {
            $result .= <<<"EOT"
            <article class="{$post->getCategorySlug()}">
                <a class="category"
                    href="{$this->getCategoryUrlStart()}{$post->getTypeSlug()}/{$post->getCategorySlug()}"
                    title="View all: {$post->getCategoryName()} / {$post->getTypeName()}"
                >
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
