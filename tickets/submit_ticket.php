<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $user_id = $_SESSION['user_id'];
    $conn = new mysqli('localhost', 'lumihost_ticketsystem', 'bACPfJTDXPkX4EKv7kMU', 'lumihost_ticketsystem');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("INSERT INTO tickets (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $subject, $message);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: tickets.php');
    exit;
}
?>