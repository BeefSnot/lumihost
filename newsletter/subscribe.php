<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $action = $_POST['action'];
        $groups = $_POST['groups'];

        if ($action === 'subscribe') {
            foreach ($groups as $group_id) {
                $stmt = $db->prepare("INSERT INTO group_subscriptions (email, group_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE group_id = ?");
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($db->error));
                }
                $stmt->bind_param("sii", $email, $group_id, $group_id);
                if ($stmt->execute() === false) {
                    die('Execute failed: ' . htmlspecialchars($stmt->error));
                }
                $stmt->close();
            }
            $message = 'Subscribed successfully';
        } elseif ($action === 'unsubscribe') {
            foreach ($groups as $group_id) {
                $stmt = $db->prepare("DELETE FROM group_subscriptions WHERE email = ? AND group_id = ?");
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($db->error));
                }
                $stmt->bind_param("si", $email, $group_id);
                if ($stmt->execute() === false) {
                    die('Execute failed: ' . htmlspecialchars($stmt->error));
                }
                $stmt->close();
            }
            $message = 'Unsubscribed successfully';
        }
    }
}

// Fetch available groups
$groupsResult = $db->query("SELECT id, name FROM groups");
$groups = [];
while ($row = $groupsResult->fetch_assoc()) {
    $groups[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe/Unsubscribe</title>
    <link rel="stylesheet" href="assets/css/newsletter.css">
</head>
<body class="dark-theme">
    <header class="hero page">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img class="img-fluid" src="../assets/img/logonew.png" alt="Lumi Host">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../about.html">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../apply.html">Apply</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../staff.php">Staff Center</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../radio/index.php">Radio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../status/index.php">Status</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mt-5">
        <div class="section-title text-center">
            <h6 class="text-uppercase text-muted">Newsletter Subscription</h6>
            <h4 class="font-weight-bold">Subscribe/Unsubscribe to Newsletters<span class="main">.</span></h4>
        </div>
        <div class="status-details text-center dark-background p-4 rounded">
            <?php if (isset($message)): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="groups">Groups:</label>
                <select id="groups" name="groups[]" multiple required>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="action">Action:</label>
                <select id="action" name="action" required>
                    <option value="subscribe">Subscribe</option>
                    <option value="unsubscribe">Unsubscribe</option>
                </select>
                <button type="submit" class="btn btn-primary mt-4">Submit</button>
            </form>
        </div>
        <div class="section-title text-center mt-5">
            <h6 class="text-uppercase text-muted">Manage Groups</h6>
            <h4 class="font-weight-bold">Create or Delete Groups<span class="main">.</span></h4>
        </div>
        <div class="status-details text-center dark-background p-4 rounded">
            <a href="groups.php" class="btn btn-primary mt-4">Manage Groups</a>
        </div>
    </main>
</body>
</html>