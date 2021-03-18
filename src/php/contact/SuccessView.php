<?php declare(strict_types=1);
namespace Netmatters\Contact;

use Psr\Log\LoggerInterface;

class SuccessView
{
    protected LoggerInterface $logger;
    protected Message $message;
    protected Validation $validation;

    function __construct(LoggerInterface $logger, Message $message, Validation $validation)
    {
        $this->logger = $logger;
        $this->message = $message;
        $this->validation = $validation;
    }

    public function html(): string
    {
        $contactMeans = '';
        if ($this->validation->getIsEmailValid() && $this->validation->getIsPhoneValid()) {
            $contactMeans = ' by email or phone';
        } else if ($this->validation->getIsEmailValid()) {
            $contactMeans = ' by email';
        } else if ($this->validation->getIsPhoneValid()) {
            $contactMeans = ' by phone';
        } else {
            $this->logger
                ->error('Generating success feedback, but message appears not to have contact means.', 
                    [$this->message, $this->validation]);
        }
        return <<<"EOT"
        <div class="feedback">
            <p>Thank you for your message, {$this->message->getName()}.</p>
            <p>We will be in contact with you soon$contactMeans.</p>
        </div>
        EOT;
    }
}
