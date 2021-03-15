<?php declare(strict_types=1);

use Netmatters\Contact\RawResults;
use PHPUnit\Framework\TestCase;

class RawResultsTest extends TestCase
{
    public function testInstantiates(): void
    {
        $raw = new RawResults('1', 'Jane Smith', 'jane@example.com',
            '(+44) 01234 555 234', '1', 'I want to improve SEO.');
        $this->assertInstanceOf(RawResults::class, $raw);
    }

    public function testStoresSubmitted(): void
    {
        $raw = new RawResults('1', 'Jane Smith', 'jane@example.com',
            '(+44) 01234 555 234', '1', 'I want to improve SEO.');
        $this->assertSame('1', $raw->getSubmitted());
    }
    public function testStoresName(): void
    {
        $raw = new RawResults('1', 'Jane Smith', 'jane@example.com',
            '(+44) 01234 555 234', '1', 'I want to improve SEO.');
        $this->assertSame('Jane Smith', $raw->getName());
    }
    public function testStoresEmail(): void
    {
        $raw = new RawResults('1', 'Jane Smith', 'jane@example.com',
            '(+44) 01234 555 234', '1', 'I want to improve SEO.');
        $this->assertSame('jane@example.com', $raw->getEmail());
    }
    public function testStoresInvalidEmail(): void
    {
        $raw = new RawResults('1', 'Jane Smith', '@example.com',
            '(+44) 01234 555 234', '1', 'I want to improve SEO.');
        $this->assertSame('@example.com', $raw->getEmail());
    }
    public function testStoresPhone(): void
    {
        $raw = new RawResults('1', 'Jane Smith', 'jane@example.com',
            '(+44) 01234 555 234', '1', 'I want to improve SEO.');
        $this->assertSame('(+44) 01234 555 234', $raw->getPhone());
    }
    public function testStoresInvalidPhone(): void
    {
        $raw = new RawResults('1', 'Jane Smith', 'jane@example.com',
            'my phone number', '1', 'I want to improve SEO.');
        $this->assertSame('my phone number', $raw->getPhone());
    }
    public function testStoresOptIn(): void
    {
        $raw = new RawResults('1', 'Jane Smith', 'jane@example.com',
            '(+44) 01234 555 234', '1', 'I want to improve SEO.');
        $this->assertSame('1', $raw->getOptIn());
    }
    public function testStoresMessage(): void
    {
        $raw = new RawResults('1', 'Jane Smith', 'jane@example.com',
            '(+44) 01234 555 234', '1', 'I want to improve SEO.');
        $this->assertSame('I want to improve SEO.', $raw->getMessage());
    }
    public function testStoresEmptyMessage(): void
    {
        $raw = new RawResults('1', 'Jane Smith', 'jane@example.com',
            '(+44) 01234 555 234', '1', '');
        $this->assertSame('', $raw->getMessage());
    }
}
