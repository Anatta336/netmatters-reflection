<?php declare(strict_types=1);

use Netmatters\Contact\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testInstantiates(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );
        $this->assertInstanceOf(Message::class, $message);
    }

    public function testStoresName(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );
        $this->assertSame('Jane Smith', $message->getName());
    }

    public function testStoresEmail(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );
        $this->assertSame('jane@example.com', $message->getEmail());
    }

    public function testStoresPhone(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );
        $this->assertSame('(+44) 01234 555 234', $message->getPhone());
    }

    public function testStoresIsOptIn(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );
        $this->assertTrue($message->getIsOptIn());
    }

    public function testStoresMessage(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );
        $this->assertSame('I want to improve SEO.', $message->getMessage());
    }

    public function testStoresTimeSent(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );
        // equals instead of same, as they'll be different DateTime objects
        $this->assertEquals(new DateTime('2021-01-02 12:00:34'), $message->getTimeSent());
    }

    /**
     * Although getTimeSent() returns a reference to an object, changing that
     * object should not alter anything on the immutable Message.
     */
    public function testCannotChangeTimeWithReturnedObject(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );

        $timeFromObject = $message->getTimeSent();
        $timeFromObject->modify('3 days');
        $this->assertNotEquals(new DateTime('2021-01-02 12:00:34'), $timeFromObject);
        $this->assertNotEquals($message->getTimeSent(), $timeFromObject);
        $this->assertEquals(new DateTime('2021-01-02 12:00:34'), $message->getTimeSent());
    }

    /**
     * Although Message is constructed using a DateTime object, changing
     * that object after Message is constructed should not change anything
     * stored on the immutable Message.
     */
    public function testCannotChangeTimeWithOriginalObject(): void
    {
        $timeUsedToCreate = new DateTime('2021-01-02 12:00:34');

        $message = new Message(
            $timeUsedToCreate,
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );

        $timeUsedToCreate->modify('3 days');
        $this->assertNotEquals($timeUsedToCreate, $message->getTimeSent());
        $this->assertEquals(new DateTime('2021-01-02 12:00:34'), $message->getTimeSent());
    }

    public function testInstantiatesWithNoValues(): void
    {
        $message = new Message();
        $this->assertInstanceOf(Message::class, $message);
    }

    public function testHasValuesWhenOneValueSet(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
        );
        $this->assertTrue($message->getHasAnyStoredValues());
    }

    public function testHasValuesWhenAllValuesSet(): void
    {
        $message = new Message(
            new DateTime('2021-01-02 12:00:34'),
            'Jane Smith',
            'jane@example.com',
            '(+44) 01234 555 234',
            true,
            'I want to improve SEO.',
        );
        $this->assertTrue($message->getHasAnyStoredValues());
    }

    public function testHasNoValuesWhenNoValuesSet(): void
    {
        $message = new Message();
        $this->assertFalse($message->getHasAnyStoredValues());
    }
    public function testHasNoValuesWhenOnlyDateSet(): void
    {
        $message = new Message(new DateTime('2021-01-02 12:00:34'));
        $this->assertFalse($message->getHasAnyStoredValues());
    }
}
