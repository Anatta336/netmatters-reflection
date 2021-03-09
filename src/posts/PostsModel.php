<?php
namespace Netmatters;

class PostsModel
{
    protected DatabaseInterface $database;

    function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }
}

