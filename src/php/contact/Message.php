<?php declare(strict_types=1);
namespace Netmatters\Contact;

use DateTime;

/**
 * Immutable object representing a message sent by a user from a contact form.
 */
class Message
{
    protected bool $hasAnyValues = false;
    protected string $name;
    protected string $email;
    protected string $phone;
    protected bool $isOptIn;
    protected string $message;
    protected \DateTime $timeSent;

    function __construct(
        ?string $name = null,
        ?string $email = null,
        ?string $phone = null,
        ?bool $isOptIn = null,
        ?string $message = null,
        ?\DateTime $timeSent = null)
    {
        if ($name != null) {
            $this->name = $name;
        }
        if ($email != null) {
            $this->email = $email;
        }
        if ($phone != null) {
            $this->phone = $phone;
        }
        if ($isOptIn != null) {
            $this->isOptIn = $isOptIn;
        }
        if ($message != null) {
            $this->message = $message;
        }

        // store reference to a local clone, so Message remains immutable
        if ($timeSent != null) {
            $this->timeSent = (clone $timeSent);
        }

        $this->hasAnyValues = (isset($this->name)
            || isset($this->email)
            || isset($this->phone)
            || isset($this->isOptIn)
            || isset($this->message)
            || isset($this->timeSent));
    }

    public function getHasAnyValues(): bool
    {
        return $this->hasAnyValues;
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
