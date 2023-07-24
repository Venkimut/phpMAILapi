<?php
require '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use SendGrid\Mail\Mail;
use Mailgun\Mailgun;

class Mailer
{
    private $mailer;
    

    public function __construct(string $mailer = 'phpmailer')
    {
        
        // Constructor initializes the selected mailer library
        switch ($mailer) {
            case 'phpmailer':
                $this->mailer = new PHPMailer();
                break;
            case 'sendgrid':
                $this->mailer = new Mail();
                break;
            case 'mailgun':
                $this->mailer = new Mailgun();
                break;
            default:
                throw new InvalidArgumentException('Invalid mailer specified.');
        }
    }

    public function setSMTPAuth(string $host, int $port, string $username, string $password): void
    {
        // Method to set SMTP authentication for PHPMailer library
        if ($this->mailer instanceof PHPMailer) {
            $this->mailer->isSMTP();
            $this->mailer->Host = $host;
            $this->mailer->Port = $port;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $username;
            $this->mailer->Password = $password;
        } else {
            throw new RuntimeException('SMTP authentication is not supported by this mailer.');
        }
    }

    public function useHTML(bool $useHTML = true): void
    {
        // Method to set whether the email uses HTML or is plain text
        $this->mailer->isHTML($useHTML);
    }

    public function addAltBody(string $altBody): void
    {
        // Method to set the alternative plain text body for the email
        $this->mailer->AltBody = $altBody;
    }

    public function addAttachment(string $path, string $filename): void
    {
        // Method to add an attachment to the email
        $this->mailer->addAttachment($path, $filename);
    }

    public function embedImage(string $path, string $cid): void
    {
        // Method to embed an image in the email (only supported by PHPMailer)
        if ($this->mailer instanceof PHPMailer) {
            $this->mailer->addEmbeddedImage($path, $cid);
        } else {
            throw new RuntimeException('Embedding images is not supported by this mailer.');
        }
    }

    public function addRecipient(array $recipient, string $type): void
    {
        // Method to add a recipient (To, Cc, Bcc) to the email
        $email = array_key_first($recipient);
        $name = $recipient[$email];
        switch ($type) {
            case 'to':
                $this->mailer->addAddress($email, $name);
                break;
            case 'cc':
                $this->mailer->addCC($email, $name);
                break;
            case 'bcc':
                $this->mailer->addBCC($email, $name);
                break;
            default:
                throw new InvalidArgumentException('Invalid recipient type specified.');
        }
    }

    public function send(): bool
    {
        try {
            if ($this->mailer instanceof PHPMailer) {
                // Sending email using PHPMailer
                return $this->mailer->send();
            } elseif ($this->mailer instanceof Mail) {
                // Sending email using SendGrid
                $sendGrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
                $response = $sendGrid->send($this->mailer);
                return $response->statusCode() === 202;
            } elseif ($this->mailer instanceof Mailgun) {
                // Sending email using Mailgun
                $this->mailer->setApiKey(getenv('MAILGUN_API_KEY'));
                $this->mailer->setDomain(getenv('MAILGUN_DOMAIN'));
                $this->mailer->send();
                return true;
            }
        } catch (\Throwable $e) {
            // Catch and handle any errors that occur during sending
            throw new RuntimeException('Error sending email: ' . $e->getMessage());
        }
    }

    public function validate(): bool
    {
        // Method to validate the recipient's email address
        // Implement email validation logic here (e.g., format, DNS checks).
        // Return true if the email is valid, otherwise return false.
        // Example:
        return filter_var($this->mailer->addAddress, FILTER_VALIDATE_EMAIL) !== false;
    }
}
