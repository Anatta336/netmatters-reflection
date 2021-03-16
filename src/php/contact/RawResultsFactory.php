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

    /**
     * Fetches the UNFILTERED value from $_POST for the given key
     * if there is a value with that key. Returns null if it's not set.
     * @param mixed $key Key to load the value for.
     * @return string|null Value stored for the given key, or null if
     * the key isn't found in $_POST.
     */
    protected function getFromPostUnfiltered($key): ?string
    {
        if (!isset($_POST)) {
            return null;
        }
        if (!isset($_POST[$key])) {
            return null;
        }
        return $_POST[$key];
    }

    public function buildResultsFromPost(): RawResults
    {
        return new RawResults(
            $this->getFromPostUnfiltered($this->fields->getSubmittedFieldName()),
            $this->getFromPostUnfiltered($this->fields->getNameFieldName()),
            $this->getFromPostUnfiltered($this->fields->getEmailFieldName()),
            $this->getFromPostUnfiltered($this->fields->getPhoneFieldName()),
            $this->getFromPostUnfiltered($this->fields->getOptInFieldName()),
            $this->getFromPostUnfiltered($this->fields->getMessageFieldName()),
        );
    }
}
