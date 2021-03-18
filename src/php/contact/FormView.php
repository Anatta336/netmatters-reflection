<?php declare(strict_types=1);
namespace Netmatters\Contact;

class FormView
{
    protected Message $message;
    protected Validation $validation;

    function __construct(Message $message, Validation $validation)
    {
        $this->message = $message;
        $this->validation = $validation;
    }

    protected function show(bool $isShown): string
    {
        return $isShown ? ' show' : '';
    }

    protected function checked(bool $isChecked): string
    {
        return $isChecked ? ' checked' : '';
    }

    /**
     * Generates HTML for a contact form, reflecting the state
     * of the Message and Validation used when creating this View.
     * @return string HTML representing a contact form.
     */
    public function htmlForm(): string
    {
        $message = $this->message;
        $validation = $this->validation;
        
        $result = <<<"EOT"
        <form class="message-form" method="POST" action="contact.php">
            <input type="hidden" name="form-submitted" value="1">
            <fieldset class="user-details">
                <legend>Your details</legend>
                <label>Name:
                    <input type="text" name="user-name" placeholder="Jane Smith" value="{$message->getName()}">
                </label>
                <p class="error{$this->show($validation->getIsFormSubmission() && !$message->getName())}" id="no-name">Please leave your name.</p>
                <div class="user-email">
                    <label>Email:
                        <input type="email" name="user-email" placeholder="jane.smith@example.com" value="{$message->getEmail()}">
                    </label>
                    <p class="error{$this->show($validation->getIsFormSubmission() && $validation->getHasEmail() && !$validation->getIsEmailValid())}" id="invalid-email">Please double-check your email address, this doesn't appear to be valid.</p>
                </div>

                <div class="user-phone">
                    <label>Telephone:
                        <input type="text" name="user-phone" maxlength="32" placeholder="01367 587621" value="{$message->getPhone()}">
                    </label>
                    <p class="error{$this->show($validation->getIsFormSubmission() && $validation->getHasPhone() && !$validation->getIsPhoneValid())}" id="invalid-phone">Please double-check phone number, what was entered doesn't appear to be valid.</p>
                    <p class="error{$this->show($validation->getIsFormSubmission() && !$validation->getHasContactMethod())}" id="no-contact">Please provide an email address or phone number so we can get back to you.</p>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="user-opt-in"{$this->checked($message->getIsOptIn())}>
                        <span class="checkmark"></span>
                        <p>Tick this box if you would like to also receive marketing information from us.<br>Please see our <a href="page/privacy-policy">Privacy Policy</a> for details on how your data is used.</p>
                    </label>
                </div>
            </fieldset>

            <fieldset class="user-message">
                <label>Your message:
                    <textarea name="user-message" cols="45" rows="10">{$message->getMessage()}</textarea>
                    <p class="error{$this->show($validation->getIsFormSubmission() && !$message->getMessage())}" id="no-message">Please leave a brief message to let us know how we can help.</p>
                </label>
            </fieldset>
            <input type="submit" value="Send">
        </form>
        EOT;

        return $result;
    }
}
