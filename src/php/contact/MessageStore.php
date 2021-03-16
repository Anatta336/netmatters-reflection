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

    public function StoreMessage(Message $message): bool
    {
        $sql = "INSERT INTO contact_message (name, email, phone, marketing_opt_in, message, time_sent)
        VALUES (?, ?, ?, ?, ?, ?);";
        //time_sent format: '2021-01-02 12:45:32'
        
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
}
