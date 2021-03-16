<?php declare(strict_types=1);

use Netmatters\Contact\FormFieldNames;
use Netmatters\Contact\FormView;
use Netmatters\Contact\MessageFactory;
use Netmatters\Contact\PhoneCleaner;
use Netmatters\Contact\RawResultsFactory;
use Netmatters\Contact\ValidateInput;
use Netmatters\Contact\MessageStore;
use Netmatters\Database\SQLiteDatabase;

require_once __DIR__ . '/../vendor/autoload.php';

// retrieve data from the form
$rawFactory = new RawResultsFactory(new FormFieldNames());
$rawResults = $rawFactory->buildResultsFromPost();

// filter and validate
$messageFactory = new MessageFactory(new PhoneCleaner());
$message = $messageFactory->createFromRaw($rawResults);
$validate = new ValidateInput($rawResults);

$feedback = '';
$hasSubmittedMessage = false;
if ($validate->getIsValid()) {
    $database = new SQLiteDatabase(__DIR__ . '/../db/netmatters.db');
    $store = new MessageStore($database);
    $hasSubmittedMessage = $store->StoreMessage($message);
    
    // TODO: better feedback messages?
    if ($hasSubmittedMessage) {
        $feedback = 'Thanks for your message!';
    } else {
        $feedback = 'Unable to submit your message.';
        // TODO: if unable to submit should display the form with what was entered
    }
}

$formView = new FormView($message, $validate);
// TODO: feedback view
// $feedbackView = ...

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
                            // TODO: replace with a feedback display view?
                            echo "<p class=\"feedback\">$feedback</a>";
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
