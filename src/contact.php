<?php declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Netmatters\Contact\FormFieldNames;
use Netmatters\Contact\FormView;
use Netmatters\Contact\MessageFactory;
use Netmatters\Contact\PhoneCleaner;
use Netmatters\Contact\RawResultsFactory;
use Netmatters\Contact\Validation;
use Netmatters\Contact\MessageStore;
use Netmatters\Database\SQLiteDatabase;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Netmatters\Contact\SuccessView;

$logger = new Logger('main_logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/full.log', Logger::DEBUG));
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/error.log', Logger::ERROR));

// retrieve data from the form
$rawFactory = new RawResultsFactory(new FormFieldNames());
$rawResults = $rawFactory->buildResultsFromPost();

// filter and validate
$messageFactory = new MessageFactory(new PhoneCleaner());
$message = $messageFactory->createFromRaw($rawResults);
$validation = new Validation($rawResults);

// if valid, store the message
$hasSubmittedMessage = false;
if ($validation->getIsValid()) {
    $database = new SQLiteDatabase($logger, __DIR__ . '/../db/netmatters.db');
    $store = new MessageStore($database);
    $hasSubmittedMessage = $store->storeMessage($message);
}

$formView = new FormView($message, $validation);
$successView = new SuccessView($logger, $message, $validation);

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
                        <?php
                        if ($hasSubmittedMessage) {
                            echo $successView->html();
                        } else {
                            echo $formView->htmlForm();
                        }
                        ?>
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
