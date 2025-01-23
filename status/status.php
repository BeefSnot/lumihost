<?php
header('Content-Type: application/json');

function ping($host, $port, $timeout) {
    $starttime = microtime(true);
    $fsock = @fsockopen($host, $port, $errno, $errstr, $timeout);
    $stoptime = microtime(true);
    $status = false;
    $responseTime = -1;

    if ($fsock) {
        fclose($fsock);
        $status = true;
        $responseTime = ($stoptime - $starttime) * 1000; // Convert to milliseconds
    }

    return ['status' => $status, 'responseTime' => $responseTime];
}

$conn = new mysqli('localhost', 'lumihost_status', 'uZKwgga7z6qQZSNMcPdQ', 'lumihost_status');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$services = $conn->query("SELECT * FROM services");
$status = [];
$totalUptime = 0;
$totalServices = $services->num_rows;

while ($service = $services->fetch_assoc()) {
    $pingResult = ping($service['host'], $service['port'], 10);
    $status[$service['service_name']] = $pingResult;

    $uptimeDataFile = 'historical_uptime_' . $service['service_name'] . '.json';
    $historicalData = [];
    if (file_exists($uptimeDataFile)) {
        $historicalData = json_decode(file_get_contents($uptimeDataFile), true);
    } else {
        $historicalData = array_fill(0, 24, 100); // Initialize with 24 hours of 100% uptime
    }

    // Update historical data
    array_shift($historicalData); // Remove the oldest entry
    $historicalData[] = $pingResult['status'] ? 100 : 0; // Add the latest uptime

    // Calculate uptime percentage
    $uptimePercentage = array_sum($historicalData) / count($historicalData);

    // Save updated historical data
    file_put_contents($uptimeDataFile, json_encode($historicalData));

    $status[$service['service_name']]['uptime'] = round($uptimePercentage, 2);
    $status[$service['service_name']]['lastChecked'] = date('Y-m-d H:i:s');

    $totalUptime += $uptimePercentage;
}

$averageUptime = $totalUptime / $totalServices;

$response = [
    'status' => $status,
    'averageUptime' => round($averageUptime, 2)
];

echo json_encode($response);
$conn->close();
?>