<?php
// Test database connection
$conn = new mysqli('localhost', 'lumihost_tickets', 'gAhA7C5jzVPQtpTP4CA6', 'lumihost_tickets');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}
$conn->close();