<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Starting test email script...<br>";

function sendTestEmail($toEmail, $toName) {
    echo "Loading email template...<br>";
    $template = file_get_contents('email_templates/status_alert.html');
    if ($template === false) {
        echo "Failed to load email template.<br>";
        return;
    }
    $template = str_replace('{{service}}', 'Test Service', $template);
    $template = str_replace('{{status}}', 'down', $template);

    $mail = new PHPMailer(true);
    try {
        echo "Configuring SMTP...<br>";
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'mail.lumihost.net'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'status@lumihost.net'; // SMTP username
        $mail->Password = 'TvNCstyeJCJcmM7c4qSL'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        echo "Adding recipient: $toEmail...<br>";
        $mail->setFrom('status@lumihost.net', 'Lumi Host Status');
        $mail->addAddress($toEmail, $toName);

        // Content
        echo "Setting email content...<br>";
        $mail->isHTML(true);
        $mail->Subject = 'Test Status Alert';
        $mail->Body = $template;

        echo "Sending email...<br>";
        $mail->send();
        echo "Test email sent successfully to $toEmail!<br>";
    } catch (Exception $e) {
        echo "Message could not be sent to $toEmail. Mailer Error: {$mail->ErrorInfo}<br>";
    }
}

// List of email addresses to test
$testEmails = [
    'james@lumihost.net' => 'James',
    // Add more emails as needed
];

// Send test email to each address
foreach ($testEmails as $email => $name) {
    sendTestEmail($email, $name);
}

echo "Test email script completed.<br>";
?>