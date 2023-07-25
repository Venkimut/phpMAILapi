## For Import Mailer Class to the existing application.

### Add env.php as below with credentials and extend the class in mailer.php
```
<?php 
    define("SMTP_SERVER", "XXXXXXXXXXXX");
    define("SMTP_USERNAME", "XXXXXXXXXX");
    define("SMTP_PASSWORD", "XXXXXXXXXX");

    define("SENDGRID_API_KEY", "XXXXXXX");

    define("MAILGUN_API_KEY", "XXXXXXXX");
    define("MAILGUN_DOMAIN", "XXXXXXXXX");
?>
```
### You can refer index.php for sample code to implement the Mailer.php


## For Use as an API Service please refer */api* with below sample JSON
```
{
  "selected_api": "sendgrid",
  "smtp_auth": true,
  "smtp_host": "smtp.example.com",
  "smtp_port": 587,
  "smtp_username": "your_smtp_username",
  "smtp_password": "your_smtp_password",
  "use_html": true,
  "alt_body": "This is the plain text version of the email.",
  "image_path": "path/to/image.jpg",
  "image_cid": "image_cid",
  "attachment_path": "path/to/attachment.pdf",
  "attachment_filename": "attachment_filename.pdf",
  "recipients": {
    "to": {
      "recipient@example.com": "Recipient Name"
    },
    "cc": {
      "cc_recipient@example.com": "CC Recipient Name"
    },
    "bcc": {
      "bcc_recipient@example.com": "BCC Recipient Name"
    }
  },
  "subject": "Test Email",
  "body": "<p>Hello, this is a test email with an embedded image.</p><img src=\"cid:image_cid\">"
}
```