<?php declare(strict_types=1);

use Netmatters\Contact\MessageStore;
use Netmatters\Database\SQLiteDatabase;

require_once __DIR__ . '/../vendor/autoload.php';

$database = new SQLiteDatabase(__DIR__ . '/../db/netmatters.db');
$storage = new MessageStore($database);
$messages = $storage->fetchAllMessages();
?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/../src/partials/head.html' ?>
<body>
    <section class="leads">
        <div class="wrapper">
            <h2>Leads</h2>
            <table>
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>optIn</th>
                    <th>message</th>
                    <th>time</th>
                </tr>
                <?php
                foreach ($messages as $message) {
                    echo "<tr>\n";
                    foreach (array_values($message) as $value) {
                        echo "<td>$value</td>\n";
                    }
                    echo "</tr>\n";
                }
                ?>
            </table>
        </div>
    </section>
</body>
</html>
