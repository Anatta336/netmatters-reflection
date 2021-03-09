<?php declare(strict_types=1);
namespace Netmatters;

class PostsController
{
    private PostsModel $model;
    private PostsView $view;

    function __construct(DatabaseInterface $database)
    {
        $this->model = new PostsModel($database);
        $this->view = new PostsView();
    }

    public function getHTMLForLatestPostsPreviews(): string
    {
        return '';
    }
}

