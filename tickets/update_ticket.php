<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $ticketId = $input['ticketId'];
    $status = $input['status'];
    $reply = $input['reply'];
    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['role'];

    $conn = new mysqli('localhost', 'lumihost_tickets', 'uncUzyW2ChkeXyX9Gw2J', 'lumihost_tickets');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is allowed to update the ticket
    if ($userRole == 'staff') {
        $stmt = $conn->prepare("UPDATE tickets SET status = ?, assigned_to = ? WHERE id = ?");
        $stmt->bind_param("sii", $status, $userId, $ticketId);
    } else {
        $stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $status, $ticketId, $userId);
    }
    $stmt->execute();
    $stmt->close();

    // Insert ticket reply
    if ($reply) {
        $stmt = $conn->prepare("INSERT INTO ticket_replies (ticket_id, user_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $ticketId, $userId, $reply);
        $stmt->execute();
        $stmt->close();
    }

    // Get ticket details
    $stmt = $conn->prepare("SELECT users.email, users.username, tickets.subject FROM tickets JOIN users ON tickets.user_id = users.id WHERE tickets.id = ?");
    $stmt->bind_param("i", $ticketId);
    $stmt->execute();
    $stmt->bind_result($email, $username, $subject);
    $stmt->fetch();
    $stmt->close();

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
        $mail->setFrom('staff@lumihost.net', 'Lumi Host');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Ticket Update: ' . $subject;
        $mail->Body = "Dear " . $username . ",<br><br>Your ticket status has been updated to: " . $status . ".";
        if ($reply) {
            $mail->Body .= "<br><br>Reply: " . $reply;
        }
        $mail->Body .= "<br><br>Thank you,<br>Lumi Host Team";

        $mail->send();
        echo 'Ticket updated successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>