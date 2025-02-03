<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'lumihost_status', 'uZKwgga7z6qQZSNMcPdQ', 'lumihost_status');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$previous_services = [
    ['service_name' => 'Website', 'host' => 'lumihost.net', 'port' => 80],
    ['service_name' => 'Nameserver 1', 'host' => 'ns1.lumihost.net', 'port' => 53],
    ['service_name' => 'Nameserver 2', 'host' => 'ns2.lumihost.net', 'port' => 53],
    ['service_name' => 'Customer Database', 'host' => 'webpanel.lumihost.net', 'port' => 3306],
    ['service_name' => 'USA Node 1', 'host' => 'radio.lumihost.net', 'port' => 80],
    ['service_name' => 'Lumi Radio', 'host' => '99.148.48.236', 'port' => 80],
    ['service_name' => 'USA Node 2 (Phoenix AZ)', 'host' => 'phoenix.lumihost.net', 'port' => 80],
];

foreach ($previous_services as $service) {
    $stmt = $conn->prepare("SELECT id FROM services WHERE service_name = ? AND host = ? AND port = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssi", $service['service_name'], $service['host'], $service['port']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO services (service_name, host, port) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssi", $service['service_name'], $service['host'], $service['port']);
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }
    }
    $stmt->close();
}

$conn->close();
echo "Status items initialized successfully.";
?>