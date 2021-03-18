<?php declare(strict_types=1);

use Netmatters\Contact\SuccessView;
use Netmatters\Contact\Message;
use Netmatters\Contact\Validation;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SuccessViewTest extends TestCase
{
    protected LoggerInterface $logger;
    protected Message $message;
    protected Validation $validation;

    protected function createStubLogger(): LoggerInterface
    {
        $stub = $this->createStub(LoggerInterface::class);
        return $stub;
    }

    protected function createStubMessage(
        bool $hasAnyStoredValues,
        string $name,
        string $email,
        string $phone,
        bool $isOptIn,
        string $message,
        DateTime $timeSent,
    ): Message
    {
        $stub = $this->createStub(Message::class);
        $stub->method('getHasAnyStoredValues')->willReturn($hasAnyStoredValues);
        $stub->method('getName')->willReturn($name);
        $stub->method('getEmail')->willReturn($email);
        $stub->method('getPhone')->willReturn($phone);
        $stub->method('getIsOptIn')->willReturn($isOptIn);
        $stub->method('getMessage')->willReturn($message);
        $stub->method('getTimeSent')->willReturn($timeSent);
        return $stub;
    }

    protected function createStubValidation(
        bool $isFormSubmission,
        bool $isValid,
        bool $hasName,
        bool $hasEmail,
        bool $hasPhone,
        bool $hasMessage,
        bool $hasContactMethod,
        bool $isEmailValid,
        bool $isPhoneValid,
    ): Validation
    {
        $stub = $this->createStub(Validation::class);
        $stub->method('getIsFormSubmission')->willReturn($isFormSubmission);
        $stub->method('getIsValid')->willReturn($isValid);
        $stub->method('getHasName')->willReturn($hasName);
        $stub->method('getHasEmail')->willReturn($hasEmail);
        $stub->method('getHasPhone')->willReturn($hasPhone);
        $stub->method('getHasMessage')->willReturn($hasMessage);
        $stub->method('getHasContactMethod')->willReturn($hasContactMethod);
        $stub->method('getIsEmailValid')->willReturn($isEmailValid);
        $stub->method('getIsPhoneValid')->willReturn($isPhoneValid);
        return $stub;
    }

    protected function setUp(): void
    {
        $this->logger = $this->createStubLogger();
        $this->message = $this->createStubMessage(true, 'Jane Smith',
            'jane@example.com', '(+44) 01234 555 234', true,
            'I want to improve SEO.', new DateTime('2021-01-02 12:00:34')
        );
        $this->validation = $this->createStubValidation(true, true, true,
            true, true, true, true, true, true);
    }

    public function testInstantiates(): void
    {
        $view = new SuccessView($this->logger, $this->message, $this->validation);
        $this->assertInstanceOf(SuccessView::class, $view);
    }
    
    public function testGeneratesHtml(): void
    {
        $expected = <<<'EOT'
        <div class="feedback">
            <p>Thank you for your message, Jane Smith.</p>
            <p>We will be in contact with you soon by email or phone.</p>
        </div>
        EOT;
        $view = new SuccessView($this->logger, $this->message, $this->validation);
        $this->assertSame($expected, $view->html());
    }

    public function testGeneratesHtmlWithOnlyEmail(): void
    {
        $expected = <<<'EOT'
        <div class="feedback">
            <p>Thank you for your message, Jane Smith.</p>
            <p>We will be in contact with you soon by email.</p>
        </div>
        EOT;

        $message = $this->createStubMessage(true, 'Jane Smith',
            'jane@example.com', '', true,
            'I want to improve SEO.', new DateTime('2021-01-02 12:00:34')
        );
        $validation = $this->createStubValidation(true, true, true,
            true, false, true, true, true, false);

        $view = new SuccessView($this->logger, $message, $validation);
        $this->assertSame($expected, $view->html());
    }

    public function testGeneratesHtmlWithOnlyPhone(): void
    {
        $expected = <<<'EOT'
        <div class="feedback">
            <p>Thank you for your message, Jane Smith.</p>
            <p>We will be in contact with you soon by phone.</p>
        </div>
        EOT;

        $message = $this->createStubMessage(true, 'Jane Smith',
            '', '(+44) 01234 555 234', true,
            'I want to improve SEO.', new DateTime('2021-01-02 12:00:34')
        );
        $validation = $this->createStubValidation(true, true, true,
            false, true, true, true, false, true);

        $view = new SuccessView($this->logger, $message, $validation);
        $this->assertSame($expected, $view->html());
    }

    public function testGeneratesHtmlAndLogWarningWithNoContact(): void
    {
        $expected = <<<'EOT'
        <div class="feedback">
            <p>Thank you for your message, Jane Smith.</p>
            <p>We will be in contact with you soon.</p>
        </div>
        EOT;

        $message = $this->createStubMessage(true, 'Jane Smith',
            '', '', true,
            'I want to improve SEO.', new DateTime('2021-01-02 12:00:34')
        );
        $validation = $this->createStubValidation(true, true, true,
            false, false, true, false, false, false);

        // expect it will attempt to log an error
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $view = new SuccessView($logger, $message, $validation);
        $this->assertSame($expected, $view->html());
    }
}
