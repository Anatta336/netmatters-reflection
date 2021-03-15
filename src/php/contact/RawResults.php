<?php declare(strict_types=1);
namespace Netmatters\Contact;

/**
 * Immutable data object representing the raw (unfiltered) data
 * captured from the contact form.
 */
class RawResults
{
    protected string $submitted;
    protected string $name;
    protected string $email;
    protected string $phone;
    protected string $optIn;
    protected string $message;

    function __construct(
        string $submitted,
        string $name,
        string $email,
        string $phone,
        string $optIn,
        string $message
    )
    {
        $this->submitted = $submitted;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->optIn = $optIn;
        $this->message = $message;
    }

    public function getSubmitted(): string
    {
        return $this->submitted;
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
    public function getOptIn(): string
    {
        return $this->optIn;
    }
    public function getMessage(): string
    {
        return $this->message;
    }
}
