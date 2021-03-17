<?php declare(strict_types=1);
namespace Netmatters\Posts;

use Netmatters\Database\DatabaseInterface;

class PostsController
{
    private PostStore $model;
    private PostsView $view;

    function __construct(DatabaseInterface $database)
    {

    }

    public function getHTMLForLatestPostsPreviews(): string
    {
        return '';
    }
}

