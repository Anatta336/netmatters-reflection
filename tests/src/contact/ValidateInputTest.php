<?php declare(strict_types=1);

use Netmatters\Contact\ValidateInput;
use Netmatters\Contact\RawResults;
use PHPUnit\Framework\TestCase;

class ValidateInputTest extends TestCase
{
    protected $rawResults;

    protected function createRawResultsStub(
        string $submitted,
        string $name,
        string $email,
        string $phone,
        string $optIn,
        string $message): RawResults
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

    public function testInstantiates(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertInstanceOf(ValidateInput::class, $validate);
    }

    // ---- getHasName ----

    public function testHasNameFalseWhenEmpty(): void
    {
        $results = $this->createRawResultsStub(
            '1', '', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getHasName());
    }
    public function testHasNameTrueWhenPresent(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getHasName());
    }
    
    // ---- getHasEmail ----

    public function testHasEmailFalseWhenEmpty(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', '', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getHasEmail());
    }
    public function testHasEmailTrueWhenPresent(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getHasEmail());
    }

    // ---- getHasPhone ----

    public function testHasPhoneFalseWhenEmpty(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getHasPhone());
    }
    public function testHasPhoneTrueWhenPresent(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getHasPhone());
    }

    // ---- getHasMessage ----

    public function testHasMessageFalseWhenEmpty(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', ''
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getHasMessage());
    }
    public function testHasMessageTrueWhenEmpty(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getHasMessage());
    }

    // ---- getIsEmailValid ----
    public function testValidEmail(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getIsEmailValid());
    }
    public function testInvalidEmailNoAtSymbol(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'janeexample.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsEmailValid());
    }
    public function testInvalidEmailNoDomain(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsEmailValid());
    }
    public function testInvalidEmailNoIdentifier(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', '@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsEmailValid());
    }
    public function testInvalidEmailBlank(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', '', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsEmailValid());
    }
    public function testInvalidEmailInvalidCharacter(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane/smith@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsEmailValid());
    }

    // ---- getIsPhoneValid ----

    public function testValidPhone(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getIsPhoneValid());
    }
    public function testInvalidPhoneBlank(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsPhoneValid());
    }
    public function testInvalidPhoneContainsLetters(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 abc 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsPhoneValid());
    }

    // ---- getHasContactMethod ----

    public function testHasContactFalseWhenNoEmailAndNoPhone(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', '', '', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getHasContactMethod());
    }
    public function testHasContactTrueWhenHasEmail(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getHasContactMethod());
    }
    public function testHasContactTrueWhenHasPhone(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', '', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getHasContactMethod());
    }
    public function testHasContactTrueWhenHasEmailAndPhone(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getHasContactMethod());
    }
    public function testHasContactFalseWhenInvalidEmailAndNoPhone(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane-example.com', '', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getHasContactMethod());
    }
    public function testHasContactFalseWhenInvalidPhoneAndNoEmail(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', '', '(+44) abcde 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getHasContactMethod());
    }
    public function testHasContactTrueWhenInvalidPhoneAndValidEmail(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) abcde 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getHasContactMethod());
    }
    public function testHasContactTrueWhenInvalidEmailAndValidPhone(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'janeexample.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getHasContactMethod());
    }

    // ---- getIsValid ----
    public function testIsValid(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getIsValid());
    }
    public function testIsValidWhenOptOut(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '0', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getIsValid());
    }
    public function testIsValidFalseWhenMissingName(): void
    {
        $results = $this->createRawResultsStub(
            '1', '', 'jane@example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsValid());
    }
    public function testIsValidFalseWhenInvalidEmail(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane-example.com', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsValid());
    }
    public function testIsValidFalseWhenInvalidPhone(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 abc 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsValid());
    }
    public function testIsValidWhenOnlyEmail(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getIsValid());
    }
    public function testIsValidWhenOnlyPhone(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', '', '(+44) 01234 555 234', '1', 'I want to improve SEO.'
        );

        $validate = new ValidateInput($results);
        $this->assertTrue($validate->getIsValid());
    }
    public function testIsValidFalseWhenNoMessage(): void
    {
        $results = $this->createRawResultsStub(
            '1', 'Jane Smith', 'jane@example.com', '(+44) 01234 555 234', '1', ''
        );

        $validate = new ValidateInput($results);
        $this->assertFalse($validate->getIsValid());
    }
}
