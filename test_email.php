<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendTestEmail($toEmail, $toName) {
    $template = file_get_contents('email_templates/ticket_update.html');
    $template = str_replace('{{username}}', $toName, $template);
    $template = str_replace('{{status}}', 'Test Status', $template);
    $template = str_replace('{{reply}}', 'This is a test reply.', $template);
    $template = str_replace('{{subject}}', 'Test Ticket Subject', $template);

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'mail.lumihost.net'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'staff@lumihost.net'; // SMTP username
        $mail->Password = '8KmCtBFC3Wca9DfzGY9w'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('staff@lumihost.net', 'Lumi Host');
        $mail->addAddress($toEmail, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Test Ticket Update: Test Ticket Subject';
        $mail->Body = $template;

        $mail->send();
        echo "Test email sent successfully to $toEmail!<br>";
    } catch (Exception $e) {
        echo "Message could not be sent to $toEmail. Mailer Error: {$mail->ErrorInfo}<br>";
    }
}

// List of email addresses to test
$testEmails = [
    'support@lumihost.net' => 'Support Team',
    'james@lumihost.net' => 'James',
    'info@lumihost.net' => 'Info Team',
    'apply@lumihost.net' => 'Apply Team',
    'billing@lumihost.net' => 'Billing Team'
    'staff@lumihost.net' => 'Staff Team'
];

// Send test email to each address
foreach ($testEmails as $email => $name) {
    sendTestEmail($email, $name);
}
?>