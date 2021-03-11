<?php declare(strict_types=1);

use Netmatters\Database\SQLiteDatabase;
use Netmatters\Images\Extensions\ExtensionCollection;
use Netmatters\Images\ImageFactory;
use Netmatters\Images\ImagesModel;
use Netmatters\Posts\PostFactory;
use Netmatters\Posts\PostsModel;
use Netmatters\Posts\PostsView;

require_once __DIR__ . '/../../vendor/autoload.php';

$database = new SQLiteDatabase(__DIR__ . '/../../db/netmatters.db');

$extensions = new ExtensionCollection();
$imageFactory = new ImageFactory($extensions);
$postFactory = new PostFactory();

$imagesModel = new ImagesModel($database, $imageFactory);
$postsModel = new PostsModel($database, $imagesModel, $postFactory);

$view = new PostsView();
echo $view->htmlLatestPosts($postsModel->getRecentPosts(3));
