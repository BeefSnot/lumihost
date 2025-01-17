<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendTestEmail($toEmail, $toName) {
    $template = file_get_contents('email_templates/status_alert.html');
    $template = str_replace('{{service}}', 'Test Service', $template);
    $template = str_replace('{{status}}', 'down', $template);

    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'mail.lumihost.net'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'status@lumihost.net'; // SMTP username
        $mail->Password = 'TvNCstyeJCJcmM7c4qSL'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('status@lumihost.net', 'Lumi Host Status');
        $mail->addAddress($toEmail, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Test Status Alert';
        $mail->Body = $template;

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
?>