<?php
    require 'mailer.php';
    $mailer = new Mailer('phpmailer');
    $host = SMTP_SERVER;
    $port = 587;
    $username = SMTP_USERNAME;
    $password = SMTP_PASSWORD;
    $mailer->setSMTPAuth($host, $port, $username, $password);
    
    // Set email content
    $mailer->useHTML(true); // Set to true for HTML content, false for plain text
    $mailer->addAltBody('This is the plain text version of the email.'); // Plain text alternative
    $mailer->embedImage('https://portfolio.muthuvenkatesh.in/assets/images/any_questions.png', 'image_cid'); // Embed an image (only supported by PHPMailer)
    $mailer->addAttachment('https://portfolio.muthuvenkatesh.in/assets/images/any_questions.png', 'attachment_filename.png'); // Add an attachment
    
    // Add recipients (you can add multiple recipients using arrays)
    $toRecipient = ['muthuvenkatesh808@gmail.com' => 'Muthu'];
    $ccRecipient = ['mail2freelancc@gmail.com' => 'Freelancc'];
    $bccRecipient = ['devenvironmentspace@gmail.com' => 'Dev'];
    $mailer->addRecipient($toRecipient, 'to');
    $mailer->addRecipient($ccRecipient, 'cc');
    $mailer->addRecipient($bccRecipient, 'bcc');
    
    // Set the email subject and body
    $subject = 'Test Email';
    $body = '<p>Hello, this is a test email with an embedded image.</p><img src="cid:image_cid">';
    
    // Set email properties
    $mailer->mailer->Subject = $subject;
    $mailer->mailer->Body = $body;
    
    // Send the email
    try {
        // $mailer->mailer->SMTPDebug = 2;
        $mail = $mailer->send();
        print_r($mail);
        echo "<br><br><br><br>Email sent successfully!";
    } catch (RuntimeException $e) {
        echo "Failed to send email: " . $e->getMessage();
    }