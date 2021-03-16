<?php declare(strict_types=1);

use Netmatters\Contact\MessageFactory;
use Netmatters\Contact\Message;
use Netmatters\Contact\PhoneCleaner;
use Netmatters\Contact\RawResults;
use PHPUnit\Framework\TestCase;

class MessageFactoryTest extends TestCase
{
    protected PhoneCleaner $cleaner;

    /**
     * Creates a stub PhoneCleaner which provides a clean method,
     * but it doesn't actually do any cleaning and will return the
     * string untouched.
     * @return PhoneCleaner A stub of a PhoneCleaner object.
     */
    protected function createStubPhoneCleaner(): PhoneCleaner
    {
        $stub = $this->createStub(PhoneCleaner::class);
        $stub->method('clean')->will($this->returnArgument(0));
        return $stub;
    }

    protected function createStubRawResults(
        ?string $submitted,
        ?string $name,
        ?string $email,
        ?string $phone,
        ?string $optIn,
        ?string $message): RawResults
    {
        $stub = $this->createStub(RawResults::class);
        $stub->method('getSubmitted')->willReturn($submitted);
        $stub->method('getName')->willReturn($name);
        $stub->method('getEmail')->willReturn($email);
        $stub->method('getPhone')->willReturn($phone);
        $stub->method('getOptIn')->willReturn($optIn);
        $stub->method('getMessage')->willReturn($message);
        return $stub;
    }

    protected function setUp(): void
    {
        $this->cleaner = $this->createStubPhoneCleaner();
    }

    public function testInstantiates(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $this->assertInstanceOf(MessageFactory::class, $factory);
    }
    
    public function testCreateEmptyGivesMessage(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $message = $factory->createEmpty();
        $this->assertInstanceOf(Message::class, $message);
    }
    public function testCreateEmptyGivesMessageWithAllNull(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $message = $factory->createEmpty();
        $this->assertSame('', $message->getEmail());
        $this->assertFalse($message->getIsOptIn());
        $this->assertSame('', $message->getMessage());
        $this->assertSame('', $message->getName());
        $this->assertSame('', $message->getPhone());
        $this->assertNull($message->getTimeSent());
    }
    public function testCreateEmptyGivesMessageWithNoValues(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $message = $factory->createEmpty();
        $this->assertFalse($message->getHasAnyStoredValues());
    }

    public function testCreateFromRaw(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $raw = $this->createStubRawResults(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );
        $message = $factory->createFromRaw($raw);
        $this->assertInstanceOf(Message::class, $message);
    }

    public function testNameStoredFromRaw(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $raw = $this->createStubRawResults(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );
        $message = $factory->createFromRaw($raw);
        $this->assertSame('Jane Smith', $message->getName());
    }
    public function testEmailStoredFromRaw(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $raw = $this->createStubRawResults(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );
        $message = $factory->createFromRaw($raw);
        $this->assertSame('jane@example.com', $message->getEmail());
    }
    public function testPhoneStoredFromRaw(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $raw = $this->createStubRawResults(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );
        $message = $factory->createFromRaw($raw);
        $this->assertSame('(+44) 01234 555 234', $message->getPhone());
    }
    public function testMessageStoredFromRaw(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $raw = $this->createStubRawResults(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );
        $message = $factory->createFromRaw($raw);
        $this->assertSame('I want to improve SEO.', $message->getMessage());
    }
    public function testOptInTrueStoredFromRaw(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $raw = $this->createStubRawResults(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );
        $message = $factory->createFromRaw($raw);
        $this->assertTrue($message->getIsOptIn());
    }
    public function testOptInFalseStoredFromRaw(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $raw = $this->createStubRawResults(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '', 'I want to improve SEO.'
        );
        $message = $factory->createFromRaw($raw);
        $this->assertFalse($message->getIsOptIn());
    }

    public function testDefaultTimeSentWhenNoneSupplied(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $raw = $this->createStubRawResults(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );
        $message = $factory->createFromRaw($raw);
        $this->assertInstanceOf(DateTime::class, $message->getTimeSent());
    }
    public function testGivenTimeSentStored(): void
    {
        $factory = new MessageFactory($this->cleaner);
        $raw = $this->createStubRawResults(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );
        $time = new DateTime('2001-02-03 04:05:06', new DateTimeZone('UTC'));
        $message = $factory->createFromRaw($raw, $time);

        // shouldn't be the same object, but should have same value
        $this->assertNotSame($time, $message->getTimeSent());
        $this->assertEquals($time, $message->getTimeSent());
    }
}
