<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $themeName = $_POST['theme_name'];
    $themeContent = $_POST['theme_content'];

    // Save the theme to the database
    $stmt = $db->prepare('INSERT INTO themes (name, content) VALUES (?, ?)');
    $stmt->bind_param('ss', $themeName, $themeContent);
    $stmt->execute();
    $stmt->close();

    $message = 'Theme created successfully';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Theme</title>
    <link rel="stylesheet" href="assets/css/newsletter.css">
</head>
<body>
    <header>
        <h1>Create Theme</h1>
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
        <h2>Create a New Theme</h2>
        <?php if (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="theme_name">Theme Name:</label>
            <input type="text" id="theme_name" name="theme_name" required>
            <label for="theme_content">Theme Content (HTML):</label>
            <textarea id="theme_content" name="theme_content" required></textarea>
            <button type="submit">Create Theme</button>
        </form>
    </main>
</body>
</html>