<?php declare(strict_types=1);
namespace Netmatters\Posts;

use DateTime;
use Netmatters\Images\Image;

/**
 * Immutable data object for a single post.
 */
class Post
{
    private string $title;
    private string $slug;
    private DateTime $date;
    private string $categoryName;
    private string $categorySlug;
    private string $typeName;
    private string $typeSlug;
    private string $posterName;
    private Image $headerImage;
    private string $contentShort;
    private Image $posterImage;

    function __construct(string $title, string $slug, DateTime $date,
        string $categoryName, string $categorySlug,
        string $typeName, string $typeSlug,
        string $posterName, Image $posterImage,
        string $contentShort, Image $headerImage)
    {
        $this->title = htmlspecialchars($title, ENT_QUOTES);
        $this->slug = htmlspecialchars($slug, ENT_QUOTES);
        $this->date = $date;
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
        return $this->date;
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
        return $this->posterImage;
    }
    public function getContentShort(): string
    {
        return $this->contentShort;
    }
    public function getHeaderImage(): Image
    {
        return $this->headerImage;
    }
}
