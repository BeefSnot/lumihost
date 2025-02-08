<?php
header('Content-Type: application/json');

function ping($host, $timeout = 5) {
    $starttime = microtime(true);
    $file      = @fsockopen($host, 80, $errno, $errstr, $timeout);
    $stoptime  = microtime(true);
    $status    = 0;

    if (!$file) {
        $status = -1;  // Site is down
    } else {
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }

    return ['status' => $status >= 0, 'responseTime' => $status];
}

$cacheFile = 'status_cache.json';
$cacheDuration = 60; // Cache duration in seconds (1 minute)

if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheDuration) {
    // Serve cached data
    echo file_get_contents($cacheFile);
    exit;
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
    $pingResult = ping($service['host'], 5);
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

// Cache the response
file_put_contents($cacheFile, json_encode($response));

echo json_encode($response);
$conn->close();
?>