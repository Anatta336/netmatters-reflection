<?php declare(strict_types=1);
namespace Netmatters\Contact;


class FormView
{

    protected function show(bool $isShown): string
    {
        return $isShown ? ' show' : '';
    }

    protected function checked(bool $isChecked): string
    {
        return $isChecked ? ' checked' : '';
    }

    public function htmlForm(FormResults $formResults): string
    {
        $result = <<<"EOT"
        <form class="message-form" method="POST" action="contact.php">
            <input type="hidden" name="form-submitted" value="1">
            <fieldset class="user-details">
                <legend>Your details</legend>
                <label>Name:
                    <input type="text" name="user-name" placeholder="Jane Smith" value="{$formResults->getName()}">
                </label>
                <p class="error{$this->show($formResults->getIsSubmitted() && !$formResults->getName())}" id="no-name">Please leave your name.</p>
                <div class="user-email">
                    <label>Email:
                        <input type="email" name="user-email" placeholder="jane.smith@example.com" value="{$formResults->getEmail()}">
                    </label>
                    <p class="error{$this->show($formResults->getIsSubmitted() && !!$formResults->getEmail() && !$formResults->getIsEmailValid())}" id="invalid-email">Please double-check your email address, this doesn't appear to be valid.</p>
                </div>
                
                <div class="user-phone">
                    <label>Telephone:
                        <input type="text" name="user-phone" maxlength="32" placeholder="01367 587621" value="{$formResults->getPhone()}">
                    </label>
                    <p class="error{$this->show($formResults->getIsSubmitted() && !$formResults->hasMeansOfContact())}" id="no-contact">Please provide an email address or phone number so we can get back to you.</p>
                </div class="user-phone">

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="user-opt-in"{$this->checked($formResults->getIsOptIn())}>
                        <span class="checkmark"></span>
                        <p>Tick this box if you would like to also receive marketing information from us.<br>Please see our <a href="https://www.netmatters.co.uk/privacy-policy">Privacy Policy</a> for details on how your data is used.</p>
                    </label>
                </div>
            </fieldset>

            <fieldset class="user-message">
                <label>Your message:
                    <textarea name="user-message" cols="45" rows="10">{$formResults->getMessage()}</textarea>
                    <p class="error{$this->show($formResults->getIsSubmitted() && !$formResults->getMessage())}" id="no-message">Please leave a brief message to let us know how we can help.</p>
                </label>
            </fieldset>

            <!--
            <div class="submit">
                <div class="g-recaptcha"
                data-sitekey="6LdkCUsaAAAAAETlRF98CiT4kvYatROoAppbpXS6"
                data-callback="captchaPassed"></div>
                <button id="send-message" type="button">Send Message</button>
            </div>
            <p class="error" id="captcha-missing">Please complete the "I'm not a robot" reCAPTCHA check before sending a message.</p>
            <script>
                // callback function from reCAPTCHA when user shows they're not a robot
                function captchaPassed() {
                    formMessages.hideError('captcha-missing');
                }
            </script>
            -->
            <input type="submit" value="Send">
        </form>
        EOT;

        return $result;
    }
}
