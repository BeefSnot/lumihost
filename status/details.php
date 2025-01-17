<?php
$service = $_GET['service'] ?? 'unknown';

function ping($host, $port, $timeout) {
    $starttime = microtime(true);
    $fsock = @fsockopen($host, $port, $errno, $errstr, $timeout);
    $stoptime = microtime(true);
    $status = 0;

    if (!$fsock) {
        $status = -1;  // Site is down
    } else {
        fclose($fsock);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }

    return $status;
}

function average_ping($host, $port, $timeout, $attempts = 5) {
    $totalPing = 0;
    $successfulPings = 0;

    for ($i = 0; $i < $attempts; $i++) {
        $ping = ping($host, $port, $timeout);
        if ($ping >= 0) {
            $totalPing += $ping;
            $successfulPings++;
        }
    }

    return $successfulPings > 0 ? floor($totalPing / $successfulPings) : -1;
}

$services = [
    'website' => ['host' => 'lumihost.net', 'port' => 80],
    'nameserver1' => ['host' => 'ns1.lumihost.net', 'port' => 53],
    'nameserver2' => ['host' => 'ns2.lumihost.net', 'port' => 53],
    'customer_database' => ['host' => 'webpanel.lumihost.net', 'port' => 3306],
    'usa_node1' => ['host' => 'radio.lumihost.net', 'port' => 80],
    'lumi_radio' => ['host' => '99.148.48.237', 'port' => 8004],
    // Add more services as needed
];

$ping = -1;
$cacheFile = 'cache_' . $service . '.json';
$cacheDuration = 60; // Cache duration in seconds (1 minute)

if (file_exists($cacheFile)) {
    $cacheData = json_decode(file_get_contents($cacheFile), true);
    if (time() - $cacheData['timestamp'] < $cacheDuration) {
        $ping = $cacheData['ping'];
        $uptime = $cacheData['uptime'];
    }
}

if ($ping == -1 && array_key_exists($service, $services)) {
    $ping = average_ping($services[$service]['host'], $services[$service]['port'], 10);

    // Load uptime data from a file (or database)
    $uptimeDataFile = 'uptime_data.json';
    $uptimeData = [];
    if (file_exists($uptimeDataFile)) {
        $uptimeData = json_decode(file_get_contents($uptimeDataFile), true);
    }

    // Calculate uptime percentage
    $uptime = 100.00; // Default value
    if (isset($uptimeData[$service])) {
        $totalChecks = $uptimeData[$service]['total_checks'];
        $upChecks = $uptimeData[$service]['up_checks'];
        $uptime = ($upChecks / $totalChecks) * 100;
        $uptime = round($uptime, 2);
    }

    // Update uptime data
    if (!isset($uptimeData[$service])) {
        $uptimeData[$service] = ['total_checks' => 0, 'up_checks' => 0];
    }
    $uptimeData[$service]['total_checks']++;
    if ($ping >= 0) {
        $uptimeData[$service]['up_checks']++;
    }
    file_put_contents($uptimeDataFile, json_encode($uptimeData));

    // Cache the results
    $cacheData = [
        'timestamp' => time(),
        'ping' => $ping,
        'uptime' => $uptime
    ];
    file_put_contents($cacheFile, json_encode($cacheData));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($service); ?> Status</title>
    <link rel="stylesheet" href="../assets/css/status.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="dark-theme">
    <header class="hero page">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img class="img-fluid" src="/assets/img/logonew.png" alt="Lumi Host">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../about.html">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../apply.html">Apply</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../staff.php">Staff Center</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../radio/index.php">Radio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../status/index.php">Status</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section id="status-details">
        <div class="container mt-5">
            <div class="section-title text-center">
                <h6><?php echo ucfirst($service); ?> Status</h6>
                <h4><?php echo ucfirst($service); ?> Details<span class="main">.</span></h4>
            </div>
            <div class="status-details text-center dark-background">
                <p>Uptime: <?php echo $uptime; ?>%</p>
                <p>Ping: <?php echo $ping >= 0 ? $ping . ' ms' : 'Down'; ?></p>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-col py-4">
                        <h5>Navigation<span class="main">.</span></h5>
                        <ul>
                            <li><a href="../index.php">Home</a></li>
                            <li><a href="../about.html">About</a></li>
                            <li><a href="../apply.html">Apply</a></li>
                            <li><a href="../staff.php">Staff Center</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-col py-4">
                        <h5>Login<span class="main">.</span></h5>
                        <ul>
                            <li><a href="https://lumihost.net">Game Panel (coming soon)</a></li>
                            <li><a href="https://webpanel.lumihost.net:2222">Web Panel</a></li>
                            <li><a href="https://billing.lumihost.net">Billing Panel</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-col py-4">
                        <h5>Legal<span class="main">.</span></h5>
                        <ul>
                            <li><a href="../tos.php">Terms of Service</a></li>
                            <li><a href="../pp.php">Privacy Policy</a></li>
                            <li><a href="../cookies.php">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <h6 class="mb-0">Copyright © 2025 Lumi Host. | All Rights Reserved |</h6>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1200,
        });
    </script>
</body>

</html>