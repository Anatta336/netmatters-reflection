<?php declare(strict_types=1);
namespace Netmatters\Contact;

/**
 * Accesses the raw (unfiltered!) values from the contact form to
 * build a RawResults object.
 * The values are intentionally not filtered, and should absolutely
 * not be trusted. See MessageFactory for getting values that are
 * suitable for storage and presentation.
 */
class RawResultsFactory
{
    protected $fields;

    function __construct(FormFieldNames $formFieldNames)
    {
        $this->fields = $formFieldNames;
    }

    public function buildResults(): RawResults
    {
        return new RawResults(
            $_POST[$this->fields->getSubmittedFieldName()],
            $_POST[$this->fields->getNameFieldName()],
            $_POST[$this->fields->getEmailFieldName()],
            $_POST[$this->fields->getPhoneFieldName()],
            $_POST[$this->fields->getOptInFieldName()],
            $_POST[$this->fields->getMessageFieldName()]
        );
    }
}
