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

$services = [
    'website' => ['host' => 'lumihost.net', 'port' => 80],
    'nameserver1' => ['host' => 'ns1.lumihost.net', 'port' => 53],
    'nameserver2' => ['host' => 'ns2.lumihost.net', 'port' => 53],
    'customer_database' => ['host' => 'webpanel.lumihost.net', 'port' => 3306],
    'usa_node1' => ['host' => 'radio.lumihost.net', 'port' => 80],
    'lumi_radio' => ['host' => '99.148.48.236', 'port' => 80],
    // Add more services as needed
];

$status = [];
$totalUptime = 0;
$totalServices = count($services);

foreach ($services as $service => $details) {
    try {
        $pingResult = ping($details['host'], $details['port'], 10);
        $status[$service] = $pingResult;

        $uptimeDataFile = 'historical_uptime_' . $service . '.json';
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

        $status[$service]['uptime'] = round($uptimePercentage, 2);
        $status[$service]['lastChecked'] = date('Y-m-d H:i:s');

        $totalUptime += $uptimePercentage;
    } catch (Exception $e) {
        $status[$service] = [
            'status' => false,
            'responseTime' => -1,
            'uptime' => 0,
            'lastChecked' => date('Y-m-d H:i:s'),
            'error' => $e->getMessage()
        ];
    }
}

$averageUptime = $totalUptime / $totalServices;

$response = [
    'status' => $status,
    'averageUptime' => round($averageUptime, 2)
];

echo json_encode($response);
?>