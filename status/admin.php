<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service = $_POST['service'];
    $issue = $_POST['issue'];

    $issuesFile = 'issues_' . $service . '.json';
    $issues = [];
    if (file_exists($issuesFile)) {
        $issues = json_decode(file_get_contents($issuesFile), true);
    }
    $issues[] = $issue;
    file_put_contents($issuesFile, json_encode($issues));

    $success = "Issue reported successfully.";
}

$services = [
    'website' => 'Website',
    'nameserver1' => 'Nameserver 1',
    'nameserver2' => 'Nameserver 2',
    'customer_database' => 'Customer Database',
    'usa_node1' => 'USA Node 1',
    'lumi_radio' => 'Lumi Radio',
    // Add more services as needed
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Lumi Host</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Report Issue</h2>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form action="admin.php" method="POST">
                    <div class="form-group">
                        <label for="service">Service</label>
                        <select class="form-control" id="service" name="service" required>
                            <?php foreach ($services as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="issue">Issue</label>
                        <textarea class="form-control" id="issue" name="issue" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Report Issue</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>