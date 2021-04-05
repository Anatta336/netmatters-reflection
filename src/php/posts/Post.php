<?php declare(strict_types=1);
namespace Netmatters\Posts;

use DateTime;
use Netmatters\Images\Image;

/**
 * Immutable data object for a single post.
 *
 * @package Post
 */
class Post
{
    /**
     * @var string Title of post.
     */
    protected string $title;

    /**
     * @var string Slug of post to be used in URL.
     */
    protected string $slug;

    /**
     * @var DateTime Time post was created.
     */
    protected DateTime $date;

    /**
     * @var string Name of post's category.
     */
    protected string $categoryName;

    /**
     * @var string Slug of category, to be used in URL.
     */
    protected string $categorySlug;

    /**
     * @var string Name of posts's type.
     */
    protected string $typeName;

    /**
     * @var string Slug of post's type, to be used in URL.
     */
    protected string $typeSlug;

    /**
     * @var string Name of the poster who created the post.
     */
    protected string $posterName;

    /**
     * @var Image Header image of the post.
     */
    protected Image $headerImage;

    /**
     * The first few lines of content to be displayed.
     * Should be plain text, without any HTML.
     *
     * @var string
     */
    protected string $contentShort;

    /**
     * @var Image Image used for the poster who created the post.
     */
    protected Image $posterImage;

    /**
     * @param string   $title
     * @param string   $slug
     * @param DateTime $date
     * @param string   $categoryName
     * @param string   $categorySlug
     * @param string   $typeName
     * @param string   $typeSlug
     * @param string   $posterName
     * @param Image    $posterImage
     * @param string   $contentShort
     * @param Image    $headerImage
     */
    public function __construct(
        string $title,
        string $slug,
        DateTime $date,
        string $categoryName,
        string $categorySlug,
        string $typeName,
        string $typeSlug,
        string $posterName,
        Image $posterImage,
        string $contentShort,
        Image $headerImage
    ) {
        $this->title        = htmlspecialchars($title, ENT_QUOTES);
        $this->slug         = htmlspecialchars($slug, ENT_QUOTES);
        $this->date         = clone $date; // clone to prevent mutability of Post
        $this->categoryName = htmlspecialchars($categoryName, ENT_QUOTES);
        $this->categorySlug = htmlspecialchars($categorySlug, ENT_QUOTES);
        $this->typeName     = htmlspecialchars($typeName, ENT_QUOTES);
        $this->typeSlug     = htmlspecialchars($typeSlug, ENT_QUOTES);
        $this->posterName   = htmlspecialchars($posterName, ENT_QUOTES);
        $this->posterImage  = $posterImage;
        $this->contentShort = htmlspecialchars($contentShort, ENT_QUOTES);
        $this->headerImage  = $headerImage;
    }

    /**
     * Get title of post.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get slug of post to be used in URL.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Get time the post was created.
     *
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        // return a reference to a clone so Post remains immutable.
        return clone $this->date;
    }

    /**
     * Get name of category for this post.
     *
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * Get slug (as used in a URL) of the category of this post.
     *
     * @return string
     */
    public function getCategorySlug(): string
    {
        return $this->categorySlug;
    }

    /**
     * Get name of type of this post.
     *
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * Get slug (as used in a URL) of the type of this post.
     *
     * @return string
     */
    public function getTypeSlug(): string
    {
        return $this->typeSlug;
    }

    /**
     * Get name of the poster of this post.
     *
     * @return string
     */
    public function getPosterName(): string
    {
        return $this->posterName;
    }

    /**
     * Get the image used by the poster of this post.
     *
     * @return Image
     */
    public function getPosterImage(): Image
    {
        // although this returns a reference, Image itself is immutable so
        // doing so doesn't break the immutability of Post.
        return $this->posterImage;
    }

    /**
     * Get the first few lines of plain text content for this post.
     *
     * @return string
     */
    public function getContentShort(): string
    {
        return $this->contentShort;
    }

    /**
     * Get the header image used for this post.
     *
     * @return Image
     */
    public function getHeaderImage(): Image
    {
        // although this returns a reference, Image itself is immutable so
        // doing so doesn't break the immutability of Post.
        return $this->headerImage;
    }
}
