<?php declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Netmatters\Database\SQLiteDatabase;
use Netmatters\Images\Extensions\ExtensionCollection;
use Netmatters\Images\ImageFactory;
use Netmatters\Images\ImagesModel;
use Netmatters\Posts\PostFactory;
use Netmatters\Posts\PostsModel;
use Netmatters\Posts\PostsView;

$database = new SQLiteDatabase(__DIR__ . '/../db/netmatters.db');

$extensions = new ExtensionCollection();
$imageFactory = new ImageFactory($extensions);
$postFactory = new PostFactory();

$imagesModel = new ImagesModel($database, $imageFactory);
$postsModel = new PostsModel($database, $imagesModel, $postFactory);

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
            echo (new PostsView())->htmlLatestPosts($postsModel->getRecentPosts(3));
            include __DIR__ . '/../src/partials/clients.html';
            include __DIR__ . '/../src/partials/footer.html';
            include __DIR__ . '/../src/partials/accreditations.html';
            ?>
        </div>
    </div>
    <?php include __DIR__ . '/../src/partials/menuContent.html' ?>
</body>
</html>
