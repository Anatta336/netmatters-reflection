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
            <section class="contact">
                <div class="wrapper">
                    <h2>Contact Us</h2>
                    <div class="content">
                        <?= $formView->htmlForm($formResults) ?>
                        <div class="contact-details">
                            <h3>Call us on:</h3>
                            <a href="tel:01603704020">01603 70 40 20</a>
                            
                            <h3>Email us on:</h3>
                            <a href="mailto:sales@netmatters.com">sales@netmatters.com</a>
                            
                            <h3>Call us at our Gorleston branch on:</h3>
                            <a href="tel:01493603204">01493 603204</a>
                            
                            <h3>Business hours:</h3>
                            <p class="business-hours">Monday - Friday 07:00 - 18:00</p>
                        </div>
                    </div>
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
