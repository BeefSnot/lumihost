<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test database connection
$conn = new mysqli('localhost', 'lumihost_ticketsystem', 'gAhA7C5jzVPQtpTP4CA6', 'lumihost_ticketsystem');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}
$conn->close();
?>