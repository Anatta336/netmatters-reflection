<!DOCTYPE html>
<html lang="en">
<?php include 'partials/head.html' ?>
<body>
    <?php include 'partials/cookieCheck.html' ?>
    <div class="page-holder">
        <div class="page-content">
            <div class="first-container">
                <div class="sticky-header">
                    <?php
                    include 'partials/header.html';
                    include 'partials/navigationBar.html';
                    ?>
                </div>
                <?php include 'partials/heroCarousel.html' ?>
            </div>
            <?php
            include 'partials/services.html';
            include 'partials/about.html';
            include 'views/latestPosts.php';
            include 'partials/clients.html';
            include 'partials/footer.html';
            include 'partials/accreditations.html';
            ?>
        </div>
    </div>
    <?php include 'partials/menuContent.html' ?>
</body>
</html>
