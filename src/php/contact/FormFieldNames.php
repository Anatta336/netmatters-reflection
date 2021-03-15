<?php declare(strict_types=1);
namespace Netmatters\Contact;

class FormFieldNames
{
    protected string $submittedFieldName = 'form-submitted';
    protected string $nameFieldName = 'user-name';
    protected string $emailFieldName = 'user-email';
    protected string $phoneFieldName = 'user-phone';
    protected string $optInFieldName = 'user-opt-in';
    protected string $messageFieldName = 'user-message';

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
}
