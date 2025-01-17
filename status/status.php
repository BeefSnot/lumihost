<?php
header('Content-Type: application/json');

function ping($host, $port, $timeout) {
    $fsock = fsockopen($host, $port, $errno, $errstr, $timeout);
    if (!$fsock) {
        return false;
    } else {
        fclose($fsock);
        return true;
    }
}

$status = [
    'Website' => ping('lumihost.net', 80, 10),
    'Nameserver 1' => ping('ns1.lumihost.net', 80, 10),
    'Nameserver 2' => ping('ns2.lumihost.net', 80, 10),
    'Customer Databases' => ping('webpanel.lumihost.net', 3306, 10),
    'US Node 1 (Tulsa OK)' => ping('radio.lumihost.net', 80, 10),

    // Add more services as needed
];

echo json_encode($status);
?>