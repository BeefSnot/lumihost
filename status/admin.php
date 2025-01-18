<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$conn = new mysqli('localhost', 'lumihost_status', 'uZKwgga7z6qQZSNMcPdQ', 'lumihost_status');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['report_issue'])) {
        $service = $_POST['service'];
        $issue = $_POST['issue'];

        $stmt = $conn->prepare("INSERT INTO issues (service, issue, status) VALUES (?, ?, 'open')");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ss", $service, $issue);
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        $success = "Issue reported successfully.";
    } elseif (isset($_POST['update_issue'])) {
        $issue_id = $_POST['issue_id'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE issues SET status = ? WHERE id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("si", $status, $issue_id);
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        $success = "Issue updated successfully.";
    }
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

$issues = $conn->query("SELECT * FROM issues")->fetch_all(MYSQLI_ASSOC);
$conn->close();
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
                    <button type="submit" class="btn btn-primary" name="report_issue">Report Issue</button>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <h2>Manage Issues</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Service</th>
                            <th>Issue</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($issues as $issue): ?>
                        <tr>
                            <td><?php echo $issue['id']; ?></td>
                            <td><?php echo $issue['service']; ?></td>
                            <td><?php echo $issue['issue']; ?></td>
                            <td><?php echo $issue['status']; ?></td>
                            <td>
                                <form action="admin.php" method="POST" class="d-inline">
                                    <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
                                    <select name="status" class="form-control d-inline w-auto">
                                        <option value="open" <?php if ($issue['status'] == 'open') echo 'selected'; ?>>Open</option>
                                        <option value="in_progress" <?php if ($issue['status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                                        <option value="closed" <?php if ($issue['status'] == 'closed') echo 'selected'; ?>>Closed</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary" name="update_issue">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>