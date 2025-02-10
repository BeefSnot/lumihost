<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';
require 'vendor/autoload.php'; // Include the Composer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '';
    $body = $_POST['body'] ?? '';
    $group_id = $_POST['group'] ?? '';

    if (empty($group_id)) {
        $message = 'Please select a group.';
    } else {
        // Fetch recipients based on group subscription
        $recipientsResult = $db->prepare('SELECT email FROM group_subscriptions WHERE group_id = ?');
        if ($recipientsResult === false) {
            error_log('Prepare failed: ' . htmlspecialchars($db->error));
            die('Prepare failed: ' . htmlspecialchars($db->error));
        }
        $recipientsResult->bind_param('i', $group_id);
        $recipientsResult->execute();
        $recipientsResult->bind_result($email);
        $recipients = [];
        while ($recipientsResult->fetch()) {
            $recipients[] = $email;
        }
        $recipientsResult->close();

        // Send the newsletter using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.lumihost.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'newsletter@lumihost.net';
            $mail->Password = 'rcfY6UFxEa2KhXcxb2LW';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('newsletter@lumihost.net', 'Lumi Host Newsletter');
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->isHTML(true);

            foreach ($recipients as $recipient) {
                $mail->addAddress($recipient);
                if (!$mail->send()) {
                    $message .= 'Mailer Error (' . htmlspecialchars($recipient) . ') ' . $mail->ErrorInfo . '<br>';
                    error_log('Mailer Error (' . htmlspecialchars($recipient) . ') ' . $mail->ErrorInfo);
                }
                $mail->clearAddresses();
            }

            if (empty($message)) {
                $message = 'Newsletter sent successfully';
            }
        } catch (Exception $e) {
            $message = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            error_log('Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}

// Fetch available groups
$groupsResult = $db->query("SELECT id, name FROM groups");
$groups = [];
while ($row = $groupsResult->fetch_assoc()) {
    $groups[] = $row;
}

// Fetch available themes
$themesResult = $db->query("SELECT id, name, content FROM themes");
$themes = [];
while ($row = $themesResult->fetch_assoc()) {
    $themes[] = $row;
}

$usersResult = $db->query("SELECT email FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Newsletter</title>
    <link rel="stylesheet" href="/newsletter/assets/css/newsletter.css">
    <script src="https://cdn.tiny.cloud/1/8sjavbgsmciibkna0zhc3wcngf5se0nri4vanzzapds2ylul/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea', // Replace with the selector for your textarea
            plugins: 'print preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            height: 500,
            menubar: 'file edit view insert format tools table help',
            content_css: '/newsletter/assets/css/newsletter.css',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });

        function loadThemeContent(themeId) {
            const themes = <?php echo json_encode($themes); ?>;
            const selectedTheme = themes.find(theme => theme.id == themeId);
            if (selectedTheme) {
                tinymce.get('body').setContent(selectedTheme.content);
            }
        }
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
                <li><a href="manage_newsletters.php">Manage Newsletters</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Send a New Newsletter</h2>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>
            <label for="body">Body:</label>
            <textarea id="body" name="body" required></textarea>
            <label for="theme">Theme:</label>
            <select id="theme" name="theme" onchange="loadThemeContent(this.value)">
                <option value="">Select a theme</option>
                <?php foreach ($themes as $theme): ?>
                    <option value="<?php echo $theme['id']; ?>"><?php echo $theme['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="group">Group:</label>
            <select id="group" name="group" required>
                <?php foreach ($groups as $group): ?>
                    <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Send</button>
        </form>
    </main>
</body>
</html>