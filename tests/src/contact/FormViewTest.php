<?php declare(strict_types=1);

use Netmatters\Contact\FormView;
use Netmatters\Contact\Message;
use Netmatters\Contact\Validation;
use PHPUnit\Framework\TestCase;

class FormViewTest extends TestCase
{
    /*
     * Most of these tests rely on comparison between multi-line
     * strings, so it's important that this file and the file defining
     * the FormView class have the same end of line encoding.
     * "Unix Style" of using LF is recommended.
     */

    protected Message $message;
    protected Validation $validation;

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
        $this->message = $this->createStubMessage(true, 'Jane Smith',
            'jane@example.com', '(+44) 01234 555 234', true,
            'I want to improve SEO.', new DateTime('2021-01-02 12:00:34')
        );
        $this->validation = $this->createStubValidation(true, true, true,
            true, true, true, true, true, true);
    }

    public function testInstantiates(): void
    {
        $view = new FormView($this->message, $this->validation);
        $this->assertInstanceOf(FormView::class, $view);
    }

    protected function createString(): string
    {
        $c = 'here';
        return <<<"EOT"
        some words
        in $c
        EOT;
    }

    public function testGenerateBlankFormWhenNoExistingSubmission(): void
    {
        $blankMessage = $this->createStubMessage(false, '', '', '',
            false, '', new DateTime()
        );
        $blankValidation = $this->createStubValidation(false, false,
            false, false, false, false, false, false, false
        );
        $view = new FormView($blankMessage, $blankValidation);
        $this->assertInstanceOf(FormView::class, $view);

        $expected = <<<"EOT"
        <form class="message-form" method="POST" action="contact.php">
            <input type="hidden" name="form-submitted" value="1">
            <fieldset class="user-details">
                <legend>Your details</legend>
                <label>Name:
                    <input type="text" name="user-name" placeholder="Jane Smith" value="">
                </label>
                <p class="error" id="no-name">Please leave your name.</p>
                <div class="user-email">
                    <label>Email:
                        <input type="email" name="user-email" placeholder="jane.smith@example.com" value="">
                    </label>
                    <p class="error" id="invalid-email">Please double-check your email address, this doesn't appear to be valid.</p>
                </div>

                <div class="user-phone">
                    <label>Telephone:
                        <input type="text" name="user-phone" maxlength="32" placeholder="01367 587621" value="">
                    </label>
                    <p class="error" id="invalid-phone">Please double-check phone number, what was entered doesn't appear to be valid.</p>
                    <p class="error" id="no-contact">Please provide an email address or phone number so we can get back to you.</p>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="user-opt-in">
                        <span class="checkmark"></span>
                        <p>Tick this box if you would like to also receive marketing information from us.<br>Please see our <a href="page/privacy-policy">Privacy Policy</a> for details on how your data is used.</p>
                    </label>
                </div>
            </fieldset>

            <fieldset class="user-message">
                <label>Your message:
                    <textarea name="user-message" cols="45" rows="10"></textarea>
                    <p class="error" id="no-message">Please leave a brief message to let us know how we can help.</p>
                </label>
            </fieldset>
            <input type="submit" value="Send">
        </form>
        EOT;

        $this->assertSame($expected, $view->htmlForm());
    }

    public function testShowAlreadyEnteredValuesOnForm(): void
    {
        $view = new FormView($this->message, $this->validation);
        $this->assertInstanceOf(FormView::class, $view);

        $expected = <<<"EOT"
        <form class="message-form" method="POST" action="contact.php">
            <input type="hidden" name="form-submitted" value="1">
            <fieldset class="user-details">
                <legend>Your details</legend>
                <label>Name:
                    <input type="text" name="user-name" placeholder="Jane Smith" value="Jane Smith">
                </label>
                <p class="error" id="no-name">Please leave your name.</p>
                <div class="user-email">
                    <label>Email:
                        <input type="email" name="user-email" placeholder="jane.smith@example.com" value="jane@example.com">
                    </label>
                    <p class="error" id="invalid-email">Please double-check your email address, this doesn't appear to be valid.</p>
                </div>

                <div class="user-phone">
                    <label>Telephone:
                        <input type="text" name="user-phone" maxlength="32" placeholder="01367 587621" value="(+44) 01234 555 234">
                    </label>
                    <p class="error" id="invalid-phone">Please double-check phone number, what was entered doesn't appear to be valid.</p>
                    <p class="error" id="no-contact">Please provide an email address or phone number so we can get back to you.</p>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="user-opt-in" checked>
                        <span class="checkmark"></span>
                        <p>Tick this box if you would like to also receive marketing information from us.<br>Please see our <a href="page/privacy-policy">Privacy Policy</a> for details on how your data is used.</p>
                    </label>
                </div>
            </fieldset>

            <fieldset class="user-message">
                <label>Your message:
                    <textarea name="user-message" cols="45" rows="10">I want to improve SEO.</textarea>
                    <p class="error" id="no-message">Please leave a brief message to let us know how we can help.</p>
                </label>
            </fieldset>
            <input type="submit" value="Send">
        </form>
        EOT;

        $this->assertSame($expected, $view->htmlForm());
    }

    public function testShowErrorOnInvalidEntries(): void
    {
        $notValid = $this->createStubValidation(true, false, false,
            true, true, false, false, false, false);
        $view = new FormView($this->message, $notValid);
        $this->assertInstanceOf(FormView::class, $view);

        $expected = <<<"EOT"
        <form class="message-form" method="POST" action="contact.php">
            <input type="hidden" name="form-submitted" value="1">
            <fieldset class="user-details">
                <legend>Your details</legend>
                <label>Name:
                    <input type="text" name="user-name" placeholder="Jane Smith" value="Jane Smith">
                </label>
                <p class="error" id="no-name">Please leave your name.</p>
                <div class="user-email">
                    <label>Email:
                        <input type="email" name="user-email" placeholder="jane.smith@example.com" value="jane@example.com">
                    </label>
                    <p class="error show" id="invalid-email">Please double-check your email address, this doesn't appear to be valid.</p>
                </div>

                <div class="user-phone">
                    <label>Telephone:
                        <input type="text" name="user-phone" maxlength="32" placeholder="01367 587621" value="(+44) 01234 555 234">
                    </label>
                    <p class="error show" id="invalid-phone">Please double-check phone number, what was entered doesn't appear to be valid.</p>
                    <p class="error show" id="no-contact">Please provide an email address or phone number so we can get back to you.</p>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="user-opt-in" checked>
                        <span class="checkmark"></span>
                        <p>Tick this box if you would like to also receive marketing information from us.<br>Please see our <a href="page/privacy-policy">Privacy Policy</a> for details on how your data is used.</p>
                    </label>
                </div>
            </fieldset>

            <fieldset class="user-message">
                <label>Your message:
                    <textarea name="user-message" cols="45" rows="10">I want to improve SEO.</textarea>
                    <p class="error" id="no-message">Please leave a brief message to let us know how we can help.</p>
                </label>
            </fieldset>
            <input type="submit" value="Send">
        </form>
        EOT;

        $this->assertSame($expected, $view->htmlForm());
    }

    public function testNotShowErrorWhenNoSubmission(): void
    {
        $notValidNotSubmitted = $this->createStubValidation(false, false, false,
            true, true, false, false, false, false);
        $view = new FormView($this->message, $notValidNotSubmitted);
        $this->assertInstanceOf(FormView::class, $view);

        $expected = <<<"EOT"
        <form class="message-form" method="POST" action="contact.php">
            <input type="hidden" name="form-submitted" value="1">
            <fieldset class="user-details">
                <legend>Your details</legend>
                <label>Name:
                    <input type="text" name="user-name" placeholder="Jane Smith" value="Jane Smith">
                </label>
                <p class="error" id="no-name">Please leave your name.</p>
                <div class="user-email">
                    <label>Email:
                        <input type="email" name="user-email" placeholder="jane.smith@example.com" value="jane@example.com">
                    </label>
                    <p class="error" id="invalid-email">Please double-check your email address, this doesn't appear to be valid.</p>
                </div>

                <div class="user-phone">
                    <label>Telephone:
                        <input type="text" name="user-phone" maxlength="32" placeholder="01367 587621" value="(+44) 01234 555 234">
                    </label>
                    <p class="error" id="invalid-phone">Please double-check phone number, what was entered doesn't appear to be valid.</p>
                    <p class="error" id="no-contact">Please provide an email address or phone number so we can get back to you.</p>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="user-opt-in" checked>
                        <span class="checkmark"></span>
                        <p>Tick this box if you would like to also receive marketing information from us.<br>Please see our <a href="page/privacy-policy">Privacy Policy</a> for details on how your data is used.</p>
                    </label>
                </div>
            </fieldset>

            <fieldset class="user-message">
                <label>Your message:
                    <textarea name="user-message" cols="45" rows="10">I want to improve SEO.</textarea>
                    <p class="error" id="no-message">Please leave a brief message to let us know how we can help.</p>
                </label>
            </fieldset>
            <input type="submit" value="Send">
        </form>
        EOT;

        $this->assertSame($expected, $view->htmlForm());
    }
}
