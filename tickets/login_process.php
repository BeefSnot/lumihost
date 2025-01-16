<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost', 'lumihost_ticketsystem', 'bACPfJTDXPkX4EKv7kMU', 'lumihost_ticketsystem');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Verify database selection
    if (!$conn->select_db('lumihost_ticketsystem')) {
        die("Database selection failed: " . $conn->error);
    }

    // Check if the table exists
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result->num_rows == 0) {
        die("Table 'users' does not exist.");
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    if (!$stmt) {
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header('Location: tickets.php');
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>