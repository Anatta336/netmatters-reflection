<?php declare(strict_types=1);

use Netmatters\Contact\FormFieldNames;
use PHPUnit\Framework\TestCase;

class FormFieldNamesTest extends TestCase
{
    public function testInstantiates(): void
    {
        $fields = new FormFieldNames();
        $this->assertInstanceOf(FormFieldNames::class, $fields);
    }

    public function testStoreSubmittedFieldName(): void
    {
        $fields = new FormFieldNames();
        $fields->setSubmittedFieldName('abc-def-01');
        $this->assertSame('abc-def-01', $fields->getSubmittedFieldName());
    }

    public function testStoreNameFieldName(): void
    {
        $fields = new FormFieldNames();
        $fields->setNameFieldName('abc-def-01');
        $this->assertSame('abc-def-01', $fields->getNameFieldName());
    }

    public function testStoreEmailFieldName(): void
    {
        $fields = new FormFieldNames();
        $fields->setEmailFieldName('abc-def-01');
        $this->assertSame('abc-def-01', $fields->getEmailFieldName());
    }

    public function testStorePhoneFieldName(): void
    {
        $fields = new FormFieldNames();
        $fields->setPhoneFieldName('abc-def-01');
        $this->assertSame('abc-def-01', $fields->getPhoneFieldName());
    }

    public function testStoreOptInFieldName(): void
    {
        $fields = new FormFieldNames();
        $fields->setOptInFieldName('abc-def-01');
        $this->assertSame('abc-def-01', $fields->getOptInFieldName());
    }

    public function testStoreMessageFieldName(): void
    {
        $fields = new FormFieldNames();
        $fields->setMessageFieldName('abc-def-01');
        $this->assertSame('abc-def-01', $fields->getMessageFieldName());
    }
}
