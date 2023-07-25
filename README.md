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

