<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $severity = $_POST['severity'];
    $user_id = $_SESSION['user_id'];
    $conn = new mysqli('localhost', 'lumihost_ticketsystem', 'gAhA7C5jzVPQtpTP4CA6', 'lumihost_ticketsystem');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("INSERT INTO tickets (user_id, subject, message, severity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $subject, $message, $severity);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: tickets.php');
    exit;
}
?>