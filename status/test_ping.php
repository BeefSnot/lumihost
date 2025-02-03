<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'google.com'; // Replace with a host you want to test
$timeout = 5;
$command = sprintf('ping -c 1 -W %d %s', $timeout, escapeshellarg($host));
$output = [];
$result = 1;
exec($command, $output, $result);

$status = ($result === 0);
$responseTime = -1;

if ($status) {
    foreach ($output as $line) {
        if (preg_match('/time=([0-9.]+) ms/', $line, $matches)) {
            $responseTime = floatval($matches[1]);
            break;
        }
    }
}

// Debugging output
echo '<pre>';
echo 'Command: ' . $command . "\n";
echo 'Result: ' . $result . "\n";
echo 'Output: ' . print_r($output, true) . "\n";
echo 'Status: ' . ($status ? 'true' : 'false') . "\n";
echo 'Response Time: ' . $responseTime . "\n";
echo '</pre>';

echo json_encode(['status' => $status, 'responseTime' => $responseTime, 'output' => $output]);
?>