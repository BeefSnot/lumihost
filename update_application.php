<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'];
    $status = $input['status'];

    // Load existing applications
    $applications_file = 'applications.json';
    $applications = [];

    if (file_exists($applications_file)) {
        $applications = json_decode(file_get_contents($applications_file), true);
    }

    // Update the status of the application
    foreach ($applications as &$application) {
        if ($application['email'] == $email) {
            $application['status'] = $status;
            break;
        }
    }

    // Save the updated applications
    file_put_contents($applications_file, json_encode($applications));

    echo "Application status updated successfully!";
} else {
    echo "Invalid request method.";
}
?>