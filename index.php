<?php
    require '/mailer.php';
    $mailer = new Mailer();

    $host = 'smtp.example.com';
    $port = 587;
    $username = 'your_smtp_username';
    $password = 'your_smtp_password';
    $mailer->setSMTPAuth($host, $port, $username, $password);
    
    // Set email content
    $mailer->useHTML(true); // Set to true for HTML content, false for plain text
    $mailer->addAltBody('This is the plain text version of the email.'); // Plain text alternative
    $mailer->embedImage('path/to/image.jpg', 'image_cid'); // Embed an image (only supported by PHPMailer)
    $mailer->addAttachment('path/to/attachment.pdf', 'attachment_filename.pdf'); // Add an attachment
    
    // Add recipients (you can add multiple recipients using arrays)
    $toRecipient = ['recipient@example.com' => 'Recipient Name'];
    $ccRecipient = ['cc_recipient@example.com' => 'CC Recipient Name'];
    $bccRecipient = ['bcc_recipient@example.com' => 'BCC Recipient Name'];
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
        $mailer->send();
        echo "Email sent successfully!";
    } catch (RuntimeException $e) {
        echo "Failed to send email: " . $e->getMessage();
    }