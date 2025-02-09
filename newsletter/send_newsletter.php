<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $theme = $_POST['theme'];
    $recipients = $_POST['recipients'];

    // Send the newsletter using PHPMailer
    require 'vendor/autoload.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'mail.lumihost.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'newsletter@lumihost.net';
    $mail->Password = 'rcfY6UFxEa2KhXcxb2LW';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('newsletter@lumihost.net', 'LumiHost Newsletter');

    foreach ($recipients as $recipient) {
        $mail->addAddress($recipient);
    }

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    if ($mail->send()) {
        $message = 'Newsletter sent successfully';
    } else {
        $message = 'Newsletter could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

$usersResult = $db->query("SELECT email FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Newsletter</title>
    <link rel="stylesheet" href="assets/css/newsletter.css">
</head>
<body>
    <header>
        <h1>Send Newsletter</h1>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="create_theme.php">Create Theme</a></li>
                <li><a href="send_newsletter.php">Send Newsletter</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Send a New Newsletter</h2>
        <?php if (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>
            <label for="body">Body:</label>
            <textarea id="body" name="body" required></textarea>
            <label for="theme">Theme:</label>
            <select id="theme" name="theme">
                <option value="default">Default</option>
                <option value="custom">Custom</option>
            </select>
            <label for="recipients">Recipients:</label>
            <select id="recipients" name="recipients[]" multiple>
                <?php while ($row = $usersResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['email']; ?>"><?php echo $row['email']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Send</button>
        </form>
    </main>
</body>
</html>