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
    if (isset($_POST['add_service'])) {
        $service_name = $_POST['service_name'];
        $host = $_POST['host'];
        $port = $_POST['port'];

        $stmt = $conn->prepare("INSERT INTO services (service_name, host, port) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $service_name, $host, $port);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['remove_service'])) {
        $service_id = $_POST['service_id'];

        $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Add previous status items if they don't exist
$previous_services = [
    ['service_name' => 'Website', 'host' => 'lumihost.net', 'port' => 80],
    ['service_name' => 'Nameserver 1', 'host' => 'ns1.lumihost.net', 'port' => 53],
    ['service_name' => 'Nameserver 2', 'host' => 'ns2.lumihost.net', 'port' => 53],
    ['service_name' => 'Customer Database', 'host' => 'webpanel.lumihost.net', 'port' => 3306],
    ['service_name' => 'USA Node 1', 'host' => 'radio.lumihost.net', 'port' => 80],
    ['service_name' => 'Lumi Radio', 'host' => '99.148.48.236', 'port' => 80],
];

foreach ($previous_services as $service) {
    $stmt = $conn->prepare("SELECT id FROM services WHERE service_name = ? AND host = ? AND port = ?");
    $stmt->bind_param("ssi", $service['service_name'], $service['host'], $service['port']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO services (service_name, host, port) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $service['service_name'], $service['host'], $service['port']);
        $stmt->execute();
    }
    $stmt->close();
}

$services = $conn->query("SELECT * FROM services");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Status Items | Lumi Host</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
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
            <div class="form-group">
                <label for="port">Port</label>
                <input type="number" class="form-control" id="port" name="port" required>
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
                    <th>Port</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($service = $services->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $service['id']; ?></td>
                    <td><?php echo $service['service_name']; ?></td>
                    <td><?php echo $service['host']; ?></td>
                    <td><?php echo $service['port']; ?></td>
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