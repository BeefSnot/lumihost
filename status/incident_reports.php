<?php
$conn = new mysqli('localhost', 'lumihost_status', 'uZKwgga7z6qQZSNMcPdQ', 'lumihost_status');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch incident reports
$issues = $conn->query("SELECT * FROM issues WHERE status != 'closed'");
if ($issues === false) {
    die("Query failed: " . $conn->error);
}
$issues = $issues->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports | Lumi Host</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/status.css">
</head>
<body>
    <div class="container mt-5">
        <div class="section-title text-center">
            <h6 class="text-uppercase text-muted">Incident Reports</h6>
            <h4 class="font-weight-bold">Current Issues<span class="main">.</span></h4>
        </div>
        <div class="issues-list text-center dark-background p-4 rounded">
            <?php if (empty($issues)): ?>
                <p class="lead">No issues reported.</p>
            <?php else: ?>
                <table class="table table-bordered table-dark">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Service</th>
                            <th>Issue</th>
                            <th>Status</th>
                            <th>Reported At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($issues as $issue): ?>
                        <tr>
                            <td><?php echo $issue['id']; ?></td>
                            <td><?php echo $issue['service']; ?></td>
                            <td><?php echo $issue['issue']; ?></td>
                            <td><?php echo $issue['status']; ?></td>
                            <td><?php echo $issue['reported_at']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>