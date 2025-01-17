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

$status = [
    'website' => ping('lumihost.net', 80, 10),
    'nameserver1' => ping('ns1.lumihost.net', 53, 10),
    'nameserver2' => ping('ns2.lumihost.net', 53, 10),
    'customer_database' => ping('webpanel.lumihost.net', 3306, 10),
    'usa_node1' => ping('radio.lumihost.net', 80, 10),
    // Add more services as needed
];

echo json_encode($status);
?>