<?php declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/../src/partials/head.html' ?>
<body>
    <?php include __DIR__ . '/../src/partials/cookieCheck.html' ?>
    <div class="page-holder">
        <div class="page-content">
            <div class="sticky-header">
                <?php
                include __DIR__ . '/../src/partials/header.html';
                include __DIR__ . '/../src/partials/navigationBar.html';
                ?>
            </div>
            <section class="contact">
                <div class="wrapper">
                    <h2>Contact Us</h2>
                    <p>Form goes here</p>
                </div>
            </section>
            <?php
            include __DIR__ . '/../src/partials/footer.html';
            include __DIR__ . '/../src/partials/accreditations.html';
            ?>
        </div>
    </div>
    <?php include __DIR__ . '/../src/partials/menuContent.html' ?>
</body>
</html>
