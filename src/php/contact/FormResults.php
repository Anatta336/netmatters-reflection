<?php declare(strict_types=1);
namespace Netmatters\Contact;

/**
 * Responsible for fetching data from a submitted form, creating
 * a Message object representing the submission.
 */
class FormResults
{
    protected string $submittedFieldName = 'form-submitted';
    protected string $nameFieldName = 'user-name';
    protected string $emailFieldName = 'user-email';
    protected string $phoneFieldName = 'user-phone';
    protected string $optInFieldName = 'user-opt-in';
    protected string $messageFieldName = 'user-message';

    protected bool $isSubmitted;
    protected string $name;
    protected string $email;
    protected string $phone;
    protected bool $isOptIn;
    protected string $message;
    protected bool $isEmailValid;

    public function getSubmittedFieldName(): string
    {
        return $this->submittedFieldName;
    }
    public function setSubmittedFieldName(string $value): string
    {
        return $this->submittedFieldName = $value;
    }

    public function getNameFieldName(): string
    {
        return $this->nameFieldName;
    }
    public function setNameFieldName(string $value): string
    {
        return $this->nameFieldName = $value;
    }

    public function getEmailFieldName(): string
    {
        return $this->emailFieldName;
    }
    public function setEmailFieldName(string $value): string
    {
        return $this->emailFieldName = $value;
    }

    public function getPhoneFieldName(): string
    {
        return $this->phoneFieldName;
    }
    public function setPhoneFieldName(string $value): string
    {
        return $this->phoneFieldName = $value;
    }

    public function getOptInFieldName(): string
    {
        return $this->optInFieldName;
    }
    public function setOptInFieldName(string $value): string
    {
        return $this->optInFieldName = $value;
    }

    public function getMessageFieldName(): string
    {
        return $this->messageFieldName;
    }
    public function setMessageFieldName(string $value): string
    {
        return $this->messageFieldName = $value;
    }

    public function getIsSubmitted(): bool
    {
        return $this->isSubmitted;
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
    public function getIsEmailValid(): bool
    {
        return $this->isEmailValid;
    }

    public function receiveFromPost()
    {
        $this->receive(INPUT_POST);
    }

    protected function receive(int $inputType)
    {
        $this->isSubmitted = isset($_POST[$this->submittedFieldName]);

        if (isset($_POST[$this->nameFieldName])) {
            $this->name = filter_input($inputType, $this->nameFieldName, FILTER_SANITIZE_STRING);
        }
    
        if (isset($_POST[$this->emailFieldName])) {
            $rawUserEmail = filter_input($inputType, $this->emailFieldName, FILTER_SANITIZE_STRING);
            $rawUserEmail = filter_input($inputType, $this->emailFieldName, FILTER_SANITIZE_STRING);
            $sanitizedEmail = filter_input($inputType, $this->emailFieldName, FILTER_SANITIZE_EMAIL);
            
            if ($rawUserEmail == $sanitizedEmail && filter_var($rawUserEmail, FILTER_VALIDATE_EMAIL)){
                $this->email = $sanitizedEmail;
                $this->isEmailValid = true;
            } else {
                $this->isEmailValid = false;
            }
        }

        if (isset($_POST[$this->phoneFieldName])) {
            $this->phone = filter_input($inputType, $this->phoneFieldName, FILTER_SANITIZE_STRING);
        }

        $this->isOptIn = isset($_POST[$this->optInFieldName]);

        if (isset($_POST[$this->messageFieldName]))
        {
            $this->message = filter_input($inputType, $this->messageFieldName, FILTER_SANITIZE_STRING);
        }
    }

    public function hasMeansOfContact(): bool
    {
        return $this->getEmail() || $this->getPhone();
    }

    public function dump()
    {
        echo "<pre>\n";
        echo $this->getName() . "\n";
        echo $this->getEmail() . "\n";
        echo "email valid? " . $this->getIsEmailValid() . "\n";
        echo $this->getPhone() . "\n";
        echo "opt in? " . $this->getIsOptIn() . "\n";
        echo $this->getMessage() . "\n";
        echo "</pre>\n";
    }
}
