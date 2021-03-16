<?php declare(strict_types=1);
namespace Netmatters\Contact;

use DateTime;

/**
 * Immutable object representing a message sent by a user from a contact form.
 */
class Message
{
    protected \DateTime $timeSent;
    protected string $name;
    protected string $email;
    protected string $phone;
    protected bool $isOptIn;
    protected string $message;

    function __construct(
        ?\DateTime $timeSent = null,
        ?string $name = null,
        ?string $email = null,
        ?string $phone = null,
        ?bool $isOptIn = null,
        ?string $message = null)
    {
        // store reference to a local clone, so Message remains immutable
        if ($timeSent != null) {
            $this->timeSent = (clone $timeSent);
        }

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
    }

    /**
     * True if any of the properties except for timeSent have a value.
     * @return bool True if one or more properties except for timeSent
     * on this object have a value.
     */
    public function getHasAnyStoredValues(): bool
    {
        return isset($this->name)
            || isset($this->email)
            || isset($this->phone)
            || isset($this->isOptIn)
            || isset($this->message);
    }

    public function getName(): string
    {
        if (isset($this->name)) {
            return $this->name;
        } else {
            return '';
        }
    }
    public function getEmail(): string
    {
        if (isset($this->email)) {
            return $this->email;
        } else {
            return '';
        }
    }
    public function getPhone(): string
    {
        if (isset($this->phone)) {
            return $this->phone;
        } else {
            return '';
        }
    }
    public function getIsOptIn(): bool
    {
        if (isset($this->isOptIn)) {
            return $this->isOptIn;
        } else {
            return false;
        }
    }
    public function getMessage(): string
    {
        if (isset($this->message)) {
            return $this->message;
        } else {
            return '';
        }
    }
    public function getTimeSent(): ?DateTime
    {
        if (isset($this->timeSent)) {
            // Message is immutable, so return reference to a clone
            return clone $this->timeSent;
        } else 
        {
            return null;
        }
    }
}
