<?php declare(strict_types=1);

use Netmatters\Contact\FormResults;
use Netmatters\Contact\FormView;

require_once __DIR__ . '/../vendor/autoload.php';

// retrieve data from the form
$formResults = new FormResults();
$formResults->receiveFromPost();

$formView = new FormView();

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
            <?php
            $formResults->dump();
            ?>
            <section class="contact">
                <div class="wrapper">
                    <h2>Contact Us</h2>
                    <?= $formView->htmlForm($formResults) ?>
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
