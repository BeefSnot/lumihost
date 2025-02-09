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

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $theme = $_POST['theme'];
    $recipients = $_POST['recipients'];

    // Debugging: Check if form data is received
    error_log("Form data received: Subject - $subject, Body - $body, Theme - $theme, Recipients - " . implode(', ', $recipients));

    // Send the newsletter using PHPMailer
    require __DIR__ . '/vendor/autoload.php'; // Updated path
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'mail.lumihost.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'newsletter@lumihost.net';
    $mail->Password = 'rcfY6UFxEa2KhXcxb2LW';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('newsletter@lumihost.net', 'Lumi Host Newsletter');

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

    // Debugging: Check if email sending process is executed
    error_log("Email sending process executed. Message: $message");
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
    <script src="https://cdn.tiny.cloud/1/8sjavbgsmciibkna0zhc3wcngf5se0nri4vanzzapds2ylul/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#body',
            plugins: 'print preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            height: 500,
            menubar: 'file edit view insert format tools table help',
            content_css: 'assets/css/newsletter.css',
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });
    </script>
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
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post" onsubmit="return validateForm()">
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
    <script>
        function validateForm() {
            alert('Form is being submitted');
            return true;
        }
    </script>
</body>
</html>