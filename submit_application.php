<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $resume = '';

    // Handle file upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $resume = 'uploads/' . basename($_FILES['resume']['name']);
        move_uploaded_file($_FILES['resume']['tmp_name'], $resume);
    }

    // Save application data to a file (or database)
    $application_data = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'position' => $position,
        'resume' => $resume,
        'status' => 'Pending'
    ];

    $applications_file = 'applications.json';
    $applications = [];

    if (file_exists($applications_file)) {
        $applications = json_decode(file_get_contents($applications_file), true);
    }

    $applications[] = $application_data;
    file_put_contents($applications_file, json_encode($applications));

    // Send confirmation email
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
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Thank you for applying at Lumi Host';
        $mail->Body = "Dear " . $name . ",<br><br>Thank you for applying for the " . $position . " position at Lumi Host. We will review your application and get back to you soon.<br><br>Thank you,<br>Lumi Host Team";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Redirect to thank you page
    header('Location: thank_you.html');
    exit;
}
?>