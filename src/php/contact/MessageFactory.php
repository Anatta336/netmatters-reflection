<?php declare(strict_types=1);
namespace Netmatters\Contact;

use DateTime;
use DateTimeZone;

class MessageFactory
{
    protected PhoneCleaner $phoneCleaner;

    function __construct(PhoneCleaner $phoneCleaner)
    {
        $this->phoneCleaner = $phoneCleaner;
    }

    /**
     * Creates a Message using the data contained in a RawResults object.
     * @param RawResults $raw The data to use to create this Message object. Any
     * amount of the properties can be omitted.
     * @param null|DateTime $timeSent The time when this message was sent. If omitted
     * the current time will be used.
     * @return Message Message object containing filtered data from the given RawResults.
     * Fields that were omitted in the RawResults will be null in the Message object.
     */
    public function createFromRaw(RawResults $raw, ?DateTime $timeSent = null): Message
    {
        $name = null;
        if ($raw->getName() !== null && $raw->getName() !== '') {
            $name = filter_var($raw->getName(), FILTER_SANITIZE_STRING);
        }
        $email = null;
        if ($raw->getEmail() !== null && $raw->getEmail() !== '') {
            $email = filter_var($raw->getEmail(), FILTER_SANITIZE_EMAIL);
        }
        $phone = null;
        if ($raw->getPhone() !== null && $raw->getPhone() !== '') {
            $phone = $this->phoneCleaner
                ->clean(filter_var($raw->getPhone(), FILTER_SANITIZE_STRING));
        }
        $isOptIn = null;
        if ($raw->getOptIn() !== null && $raw->getOptIn() !== '') {
            $isOptIn = (bool) $raw->getOptIn();
        }
        $message = null;
        if ($raw->getMessage() !== null && $raw->getMessage() !== '') {
            $message = filter_var($raw->getMessage(), FILTER_SANITIZE_STRING);
        }
        
        if ($timeSent == null) {
            $timeSent = new DateTime('now', new DateTimeZone('UTC'));
        }

        return new Message($timeSent, $name, $email, $phone, $isOptIn, $message);
    }

    /**
     * Creates an empty Message object, with all fields null.
     * @return Message 
     */
    public function createEmpty(): Message
    {
        return new Message();
    }
}
