<?php declare(strict_types=1);
namespace Netmatters\Posts;

use Netmatters\Images\ImageView;

class PostsView
{
    private string $categoryUrlStart = 'https://www.netmatters.co.uk/';
    private string $articleUrlStart = 'https://www.netmatters.co.uk/';
    private string $imageUrlStart = '';

    public function getCategoryUrlStart(): string
    {
        return $this->categoryUrlStart;
    }
    public function setCategoryUrlStart(string $value): string
    {
        $this->categoryUrlStart = $value;
        return $this->categoryUrlStart;
    }

    public function getArticleUrlStart(): string
    {
        return $this->articleUrlStart;
    }
    public function setArticleUrlStart(string $value): string
    {
        $this->articleUrlStart = $value;
        return $this->articleUrlStart;
    }

    public function getImageUrlStart(): string
    {
        return $this->imageUrlStart;
    }
    public function setImageUrlStart(string $value): string
    {
        $this->imageUrlStart = $value;
        return $this->imageUrlStart;
    }

    public function htmlLatestPosts(array $posts): string
    {
        /** @var ImageView $imageView */
        $imageView = new ImageView();

        /** @var string $result */
        $result = "";
        $result .= "<section class=\"latest-posts\">\n"
            . "<div class=\"heading-wrapper\">\n"
            . "<h2>Latest</h2>\n"
            . "</div>\n"
            . "<div class=\"wrapper\">\n"
            . "<div class=\"content\">\n";

        /** @var Post $post */
        foreach ($posts as $post) {

            $result .= "<article class=\"" . $post->getCategorySlug() . "\">\n"
                . "<a class=\"category\" href=\""
                . $this->getCategoryUrlStart() . $post->getTypeSlug() . "/" . $post->getCategorySlug()
                . "\" title=\"View all: " . $post->getCategoryName() . " / " . $post->getTypeName() . "\">\n";

            $result .= $post->getTypeName() . "\n";
            $result .= "<div class=\"tooltip-wrapper\">"
                . "<div class=\"tooltip-content\">"
                . "<p>View all: " . $post->getCategoryName() . " / " . $post->getTypeName() . "</p>\n"
                . "</div>\n"
                . "</div>\n"
                . "</a>\n";

            $result .= "<a class=\"lead\" href=\""
                . $this->getArticleUrlStart() . $post->getSlug() . "\">\n";

            $result .= $imageView
                ->pictureHtml($post->getHeaderImage(), $this->getImageUrlStart(), $post->getTitle());

            $result .= "</a>\n";

            $result .= "<a href=\"" . $this->getArticleUrlStart() . $post->getSlug() . "\">\n"
                . "<h3>" . $post->getTitle() . "</h3>\n"
                . "</a>\n";
            $result .= "<p>" . $post->getContentShort() . "&#8230;</p>\n";     
            $result .= "<a class=\"button\" href=\"" . $this->getArticleUrlStart() . $post->getSlug() . "\" "
                . "aria-label=\"Read more\">\n"
                . "Read More\n"
                . "</a>\n";

            $result .= "<div class=\"poster\">\n";

            $result .= $imageView
                ->pictureHtml($post->getPosterImage(), $this->getImageUrlStart(), $post->getPosterName());

            $result .= "<p>Posted by " . $post->getPosterName() . "</p>\n";
            $result .= "<p class=\"date\">" . $post->getDate()->format('jS F Y') . "</p>\n";
            $result .= "</div>\n";
            $result .= "</article>\n";
        }

        $result .= "</div>\n"
            . "</div>\n"
            . "</section>\n";
        
        return $result;
    }
}
