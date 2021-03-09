<?php declare(strict_types=1);

use Netmatters\Database\SQLiteDatabase;
use Netmatters\Posts\PostsModel;

require_once 'vendor/autoload.php';

$database = new SQLiteDatabase('db/netmatters.db');
$model = new PostsModel($database);

var_dump($model->getRecentPosts(1));
