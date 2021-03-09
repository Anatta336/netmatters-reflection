<?php declare(strict_types=1);
namespace Netmatters\Posts;

use Netmatters\Database\DatabaseInterface;

class PostsController
{
    private PostsModel $model;
    private PostsView $view;

    function __construct(DatabaseInterface $database)
    {

    }

    public function getHTMLForLatestPostsPreviews(): string
    {
        return '';
    }
}

