<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'];
    $status = $input['status'];
    $comment = isset($input['comment']) ? $input['comment'] : '';

    // Load existing applications
    $applications_file = 'applications.json';
    $applications = [];

    if (file_exists($applications_file)) {
        $applications = json_decode(file_get_contents($applications_file), true);
    }

    // Update the status of the application
    foreach ($applications as &$application) {
        if ($application['email'] == $email) {
            $application['status'] = $status;
            if ($comment) {
                $application['comment'] = $comment;
            }
            break;
        }
    }

    // Save the updated applications
    file_put_contents($applications_file, json_encode($applications));

    // Send email notification
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
        $mail->setFrom('your-email@example.com', 'Lumi Host');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Job Application Status at Lumi Host';
        $mail->Body = "Dear " . $application['name'] . ",<br><br>Your job application status has been updated to: " . $status . ".";
        if ($comment) {
            $mail->Body .= "<br><br>Comment: " . $comment;
        }
        $mail->Body .= "<br><br>Thank you,<br>Lumi Host Team";

        $mail->send();
        echo 'Application status updated successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request method.";
}
?>