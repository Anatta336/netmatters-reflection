<?php declare(strict_types=1);
namespace Netmatters\Contact;

use DateTime;

/**
 * Immutable object representing a message sent by a user from a contact form.
 */
class Message
{
    protected string $name;
    protected string $email;
    protected string $phone;
    protected bool $isOptIn;
    protected string $message;
    protected \DateTime $timeSent;

    function __construct(string $name, string $email, string $phone,
        bool $isOptIn, string $message, \DateTime $timeSent)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->isOptIn = $isOptIn;
        $this->message = $message;

        // store reference to a local clone, so Message remains immutable
        $this->timeSent = (clone $timeSent);
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getIsOptIn(): bool
    {
        return $this->isOptIn;
    }
    public function getMessage(): string
    {
        return $this->message;
    }
    public function getTimeSent(): DateTime
    {
        // Message is immutable, so give reference to a clone
        return clone $this->timeSent;
    }
}
