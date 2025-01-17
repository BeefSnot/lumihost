<?php
header('Content-Type: application/json');

function ping($host, $port, $timeout) {
    $fsock = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if (!$fsock) {
        return false;
    } else {
        fclose($fsock);
        return true;
    }
}

$services = [
    'website' => ['host' => 'lumihost.net', 'port' => 80],
    'nameserver1' => ['host' => 'ns1.lumihost.net', 'port' => 53],
    'nameserver2' => ['host' => 'ns2.lumihost.net', 'port' => 53],
    'database' => ['host' => 'webpanel.lumihost.net', 'port' => 3306],
    'usa_node1' => ['host' => 'radio.lumihost.net', 'port' => 80],
    'lumi_radio' => ['host' => '99.148.48.237', 'port' => 80],
    // Add more services as needed
];

$status = [];
$totalUptime = 0;
$totalServices = count($services);

foreach ($services as $service => $details) {
    $status[$service] = ping($details['host'], $details['port'], 10);
    $uptimeDataFile = 'historical_uptime_' . $service . '.json';
    $historicalData = [];
    if (file_exists($uptimeDataFile)) {
        $historicalData = json_decode(file_get_contents($uptimeDataFile), true);
    }
    $totalUptime += array_sum($historicalData) / count($historicalData);
}

$averageUptime = $totalUptime / $totalServices;

$response = [
    'status' => $status,
    'averageUptime' => round($averageUptime, 2)
];

echo json_encode($response);
?>