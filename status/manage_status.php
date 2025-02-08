<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$conn = new mysqli('localhost', 'lumihost_status', 'uZKwgga7z6qQZSNMcPdQ', 'lumihost_status');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cacheFile = 'status_cache.json';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_service'])) {
        $service_name = $_POST['service_name'];
        $host = $_POST['host'];

        $stmt = $conn->prepare("INSERT INTO services (service_name, host) VALUES (?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ss", $service_name, $host);
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        // Invalidate cache
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    } elseif (isset($_POST['remove_service'])) {
        $service_id = $_POST['service_id'];

        $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("i", $service_id);
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        // Invalidate cache
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
}

$services = $conn->query("SELECT * FROM services");
if ($services === false) {
    die("Query failed: " . $conn->error);
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Status Items | Lumi Host</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    </head>
<body>
    <div class="container mt-5">
        <h2>Manage Status Items</h2>
        <form action="manage_status.php" method="POST" class="mb-4">
            <div class="form-group">
                <label for="service_name">Service Name</label>
                <input type="text" class="form-control" id="service_name" name="service_name" required>
            </div>
            <div class="form-group">
                <label for="host">Host</label>
                <input type="text" class="form-control" id="host" name="host" required>
            </div>
            <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
        </form>
        <h3>Existing Services</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Service Name</th>
                    <th>Host</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($service = $services->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $service['id']; ?></td>
                    <td><?php echo $service['service_name']; ?></td>
                    <td><?php echo $service['host']; ?></td>
                    <td>
                        <form action="manage_status.php" method="POST" class="d-inline">
                            <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                            <button type="submit" name="remove_service" class="btn btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>