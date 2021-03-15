<?php declare(strict_types=1);

use Netmatters\Contact\FormFieldNames;
use Netmatters\Contact\RawResultsFactory;
use PHPUnit\Framework\TestCase;

class RawResultsFactoryTest extends TestCase
{
    protected FormFieldNames $fields;

    protected function setUp(): void
    {
        // create stub FormFieldsName
        $stub = $this->createStub(FormFieldNames::class);
        $stub->method('getSubmittedFieldName')->willReturn('form-submitted');
        $stub->method('getNameFieldName')->willReturn('user-name');
        $stub->method('getEmailFieldName')->willReturn('user-email');
        $stub->method('getPhoneFieldName')->willReturn('user-phone');
        $stub->method('getOptInFieldName')->willReturn('user-opt-in');
        $stub->method('getMessageFieldName')->willReturn('user-message');
        $this->fields = $stub;

        // just for these tests, overwrite the superglobal
        $_POST = [
            'form-submitted' => '1',
            'user-name' => 'Jane Smith',
            'user-email' => 'jane@example.com',
            'user-phone' => '(+44) 01234 555 234',
            'user-opt-in' => '1',
            'user-message' => 'I want to improve SEO.',
        ];
    }

    public function testInstantiates(): void
    {
        $factory = new RawResultsFactory($this->fields);
        $this->assertInstanceOf(RawResultsFactory::class, $factory);
    }

    public function testStoresRawSubmitted(): void
    {
        $factory = new RawResultsFactory($this->fields);
        $_POST['form-submitted'] = 'arbitrary';
        $results = $factory->buildResults();
        $this->assertSame('arbitrary', $results->getSubmitted());
    }
    public function testStoresRawName(): void
    {
        $factory = new RawResultsFactory($this->fields);
        $_POST['user-name'] = 'Some namé!';
        $results = $factory->buildResults();
        $this->assertSame('Some namé!', $results->getName());
    }
    public function testStoresRawEmail(): void
    {
        $factory = new RawResultsFactory($this->fields);
        $_POST['user-email'] = 'not a valid email';
        $results = $factory->buildResults();
        $this->assertSame('not a valid email', $results->getEmail());
    }
    public function testStoresRawPhone(): void
    {
        $factory = new RawResultsFactory($this->fields);
        $_POST['user-phone'] = 'undefined';
        $results = $factory->buildResults();
        $this->assertSame('undefined', $results->getPhone());
    }
    public function testStoresRawOptIn(): void
    {
        $factory = new RawResultsFactory($this->fields);
        $_POST['user-opt-in'] = '0';
        $results = $factory->buildResults();
        $this->assertSame('0', $results->getOptIn());
    }
    public function testStoresRawMessage(): void
    {
        $factory = new RawResultsFactory($this->fields);
        $_POST['user-message'] = '<script>alert(1)</script>';
        $results = $factory->buildResults();
        $this->assertSame('<script>alert(1)</script>', $results->getMessage());
    }
}
