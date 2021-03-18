<?php declare(strict_types=1);
namespace Netmatters\Posts;

use DateTime;
use Netmatters\Images\Image;

/**
 * Immutable data object for a single post.
 */
class Post
{
    protected string $title;
    protected string $slug;
    protected DateTime $date;
    protected string $categoryName;
    protected string $categorySlug;
    protected string $typeName;
    protected string $typeSlug;
    protected string $posterName;
    protected Image $headerImage;
    protected string $contentShort;
    protected Image $posterImage;

    function __construct(string $title, string $slug, DateTime $date,
        string $categoryName, string $categorySlug,
        string $typeName, string $typeSlug,
        string $posterName, Image $posterImage,
        string $contentShort, Image $headerImage)
    {
        $this->title = htmlspecialchars($title, ENT_QUOTES);
        $this->slug = htmlspecialchars($slug, ENT_QUOTES);
        $this->date = clone $date; // clone to prevent mutability of Post
        $this->categoryName = htmlspecialchars($categoryName, ENT_QUOTES);
        $this->categorySlug = htmlspecialchars($categorySlug, ENT_QUOTES);
        $this->typeName = htmlspecialchars($typeName, ENT_QUOTES);
        $this->typeSlug = htmlspecialchars($typeSlug, ENT_QUOTES);
        $this->posterName = htmlspecialchars($posterName, ENT_QUOTES);
        $this->posterImage = $posterImage;
        $this->contentShort = htmlspecialchars($contentShort, ENT_QUOTES);
        $this->headerImage = $headerImage;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function getDate(): DateTime
    {
        // return a reference to a clone so Post remains immutable.
        return clone $this->date;
    }
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }
    public function getCategorySlug(): string
    {
        return $this->categorySlug;
    }
    public function getTypeName(): string
    {
        return $this->typeName;
    }
    public function getTypeSlug(): string
    {
        return $this->typeSlug;
    }
    public function getPosterName(): string
    {
        return $this->posterName;
    }
    public function getPosterImage(): Image
    {
        // although this returns a reference, Image itself is immutable so
        // doing so doesn't break the immutability of Post.
        return $this->posterImage;
    }
    public function getContentShort(): string
    {
        return $this->contentShort;
    }
    public function getHeaderImage(): Image
    {
        // although this returns a reference, Image itself is immutable so
        // doing so doesn't break the immutability of Post.
        return $this->headerImage;
    }
}
