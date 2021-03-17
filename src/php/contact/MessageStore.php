<?php declare(strict_types=1);
namespace Netmatters\Contact;

use Netmatters\Contact\Message;
use Netmatters\Database\DatabaseInterface;

class MessageStore
{
    protected DatabaseInterface $database;

    function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function storeMessage(Message $message): bool
    {
        $sql = "INSERT INTO contact_message (name, email, phone, marketing_opt_in, message, time_sent) VALUES (?, ?, ?, ?, ?, ?);";
        
        $values = [
            $message->getName(),
            $message->getEmail(),
            $message->getPhone(),
            $message->getIsOptIn() ? 1 : 0,
            $message->getMessage(),
            $message->getTimeSent()->format('Y-m-d H:i:s'),
        ];

        var_dump($sql);
        echo "\n";
        var_dump($values);

        return $this->database->runQuery($sql, ...$values);
    }

    public function fetchAllMessages(): array
    {
        $sql = "SELECT * FROM contact_message ORDER BY time_sent DESC;";
        return $this->database->fetchResults($sql);
    }
}
