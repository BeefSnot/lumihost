<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $resume = '';

    // Handle file upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $resume = 'uploads/' . basename($_FILES['resume']['name']);
        move_uploaded_file($_FILES['resume']['tmp_name'], $resume);
    }

    // Save application data to a file (or database)
    $application_data = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'position' => $position,
        'resume' => $resume,
        'status' => 'Pending'
    ];

    $applications_file = 'applications.json';
    $applications = [];

    if (file_exists($applications_file)) {
        $applications = json_decode(file_get_contents($applications_file), true);
    }

    $applications[] = $application_data;
    file_put_contents($applications_file, json_encode($applications));

    echo "Application submitted successfully!";
} else {
    echo "Invalid request method.";
}
?>