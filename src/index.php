<?php declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Netmatters\Database\SQLiteDatabase;
use Netmatters\Images\Extensions\ExtensionCollection;
use Netmatters\Images\ImageFactory;
use Netmatters\Images\ImageStore;
use Netmatters\Posts\PostFactory;
use Netmatters\Posts\PostStore;
use Netmatters\Posts\PostsView;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('main_logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/full.log', Logger::DEBUG));
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/error.log', Logger::ERROR));

$database = new SQLiteDatabase($logger, __DIR__ . '/../db/netmatters.db');

$extensions = new ExtensionCollection();
$imageFactory = new ImageFactory($logger, $extensions);
$postFactory = new PostFactory();

$imageStore = new ImageStore($database, $imageFactory);
$postStore = new PostStore($logger, $database, $imageStore, $postFactory);

$postsView = new PostsView();
?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/../src/partials/head.html' ?>
<body>
    <?php include __DIR__ . '/../src/partials/cookieCheck.html' ?>
    <div class="page-holder">
        <div class="page-content">
            <div class="first-container">
                <div class="sticky-header">
                    <?php
                    include __DIR__ . '/../src/partials/header.html';
                    include __DIR__ . '/../src/partials/navigationBar.html';
                    ?>
                </div>
                <?php include __DIR__. '/../src/partials/heroCarousel.html' ?>
            </div>
            <?php
            include __DIR__ . '/../src/partials/services.html';
            include __DIR__ . '/../src/partials/about.html';
            echo (new PostsView())->htmlLatestPosts($postStore->getRecentPosts(3));
            include __DIR__ . '/../src/partials/clients.html';
            include __DIR__ . '/../src/partials/footer.html';
            include __DIR__ . '/../src/partials/accreditations.html';
            ?>
        </div>
    </div>
    <?php include __DIR__ . '/../src/partials/menuContent.html' ?>
</body>
</html>
