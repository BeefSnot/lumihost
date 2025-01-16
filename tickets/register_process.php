<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $conn = new mysqli('localhost', 'lumihost_tickets', 'uncUzyW2ChkeXyX9Gw2J', 'lumihost_tickets');
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $username, $email, $password);
    if ($stmt->execute() === false) {
        error_log("Execute failed: " . $stmt->error);
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    header('Location: login.php');
    exit;
} else {
    die("Invalid request method.");
}
?>