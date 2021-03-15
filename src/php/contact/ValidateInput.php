<?php declare(strict_types=1);
namespace Netmatters\Contact;

class ValidateInput
{    
    /**
     * @var RawResults The results that have been checked.
     */
    protected RawResults $rawResults;

    function __construct(RawResults $rawResults)
    {
        $this->rawResults = $rawResults;
    }

    /**
     * True if there's zero validation problems.
     * @return bool
     */
    public function getIsValid(): bool
    {
        return $this->getHasName()
            && $this->getHasContactMethod()
            && $this->getHasMessage();
    }

    /**
     * True if name field is not empty.
     * @return bool
     */
    public function getHasName(): bool
    {
        return $this->rawResults->getName() !== '';
    }
    
    /**
     * True if email field is not empty.
     * @return bool
     */
    public function getHasEmail(): bool
    {
        return $this->rawResults->getEmail() !== '';
    }

    /**
     * True if phone field is not empty.
     * @return bool
     */
    public function getHasPhone(): bool
    {
        return $this->rawResults->getPhone() !== '';
    }

    /**
     * True if message field is not empty.
     * @return bool
     */
    protected function getHasMessage(): bool
    {
        return $this->rawResults->getPhone() !== '';
    }

    /**
     * True if at least one valid email or phone number are provided.
     * @return bool
     */
    public function getHasContactMethod(): bool
    {
        return ($this->getHasEmail() && $this->getIsEmailValid())
            || ($this->getHasPhone() && $this->getIsPhoneValid());
    }

    /**
     * True if provided email appears to be a valid address.
     * If no email is provided, returns false.
     * @return bool
     */
    public function getIsEmailValid(): bool
    {
        $raw = $this->rawResults->getEmail();
        if ($raw === '') {
            return false;
        }

        return (filter_var($raw, FILTER_VALIDATE_EMAIL));
    }

    /**
     * True if provided phone number appears to be valid.
     * If no phone number is provided, returns false.
     * @return bool
     */
    public function getIsPhoneValid(): bool
    {
        $raw = $this->rawResults->getPhone();
        if ($raw === '') {
            return false;
        }

        $sanitized = filter_var($raw, FILTER_SANITIZE_STRING);

        $phoneCleaner = new PhoneCleaner();
        $cleaned = $phoneCleaner->clean($sanitized);

        return $raw === $cleaned;
    }
}
