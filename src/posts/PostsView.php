<?php declare(strict_types=1);
namespace Netmatters\Posts;

class PostsView
{
    private array $posts;

    function __construct(array $posts)
    {
        $this->posts = $posts;
    }
}
