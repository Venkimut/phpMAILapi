<?php

// Load Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Mailer;

// Check if the request is a POST request with JSON payload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Get the JSON data from the request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Initialize the Mailer class with the specified mailer library
    $selectedApi = strtolower($requestData['selected_api'] ?? 'phpmailer');

    if ($selectedApi === 'sendgrid') {
        // Sending email using SendGrid
        $mailer = new Mailer('sendgrid');
    } elseif ($selectedApi === 'mailgun') {
        // Sending email using Mailgun
        $mailer = new Mailer('mailgun');
    } else {
        // Default to PHPMailer
        $mailer = new Mailer('phpmailer');
    }
    // Set up SMTP authentication if required (optional)
    if (isset($requestData['smtp_auth']) && $requestData['smtp_auth']) {
        $host = $requestData['smtp_host'];
        $port = $requestData['smtp_port'];
        $username = $requestData['smtp_username'];
        $password = $requestData['smtp_password'];
        $mailer->setSMTPAuth($host, $port, $username, $password);
    }

    // Set email content
    $mailer->useHTML(isset($requestData['use_html']) ? $requestData['use_html'] : true);
    $mailer->addAltBody(isset($requestData['alt_body']) ? $requestData['alt_body'] : '');

    if (isset($requestData['image_path']) && isset($requestData['image_cid'])) {
        $mailer->embedImage($requestData['image_path'], $requestData['image_cid']);
    }

    if (isset($requestData['attachment_path']) && isset($requestData['attachment_filename'])) {
        $mailer->addAttachment($requestData['attachment_path'], $requestData['attachment_filename']);
    }

    // Add recipients (you can add multiple recipients using arrays)
    if (isset($requestData['recipients'])) {
        foreach ($requestData['recipients'] as $recipientType => $recipients) {
            foreach ($recipients as $recipient) {
                $mailer->addRecipient($recipient, $recipientType);
            }
        }
    }

    // Set the email subject and body
    if (isset($requestData['subject'])) {
        $mailer->mailer->Subject = $requestData['subject'];
    }

    if (isset($requestData['body'])) {
        $mailer->mailer->Body = $requestData['body'];
    }

    // Choose the API to use and send the email

    try {
       

        $mailer->send();
        $response = ['success' => true, 'message' => 'Email sent successfully!'];
    } catch (RuntimeException $e) {
        $response = ['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request. Please use POST with JSON payload.'];
}

// Set the response headers to indicate JSON content
header('Content-Type: application/json');

// Send the JSON response
echo json_encode($response);
