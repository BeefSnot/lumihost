<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$service = $_GET['service'] ?? 'unknown';
$uptime = 100.00; // Initialize uptime with a default value

function ping($host, $timeout = 5) {
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

    // Debugging information
    error_log("Ping to $host: status=$status, responseTime=$responseTime ms");

    return ['status' => $status, 'responseTime' => $responseTime];
}

$services = [
    'website' => ['host' => 'lumihost.net'],
    'nameserver1' => ['host' => 'ns1.lumihost.net'],
    'nameserver2' => ['host' => 'ns2.lumihost.net'],
    'customer_database' => ['host' => 'webpanel.lumihost.net'],
    'usa_node1' => ['host' => 'radio.lumihost.net'],
    'lumi_radio' => ['host' => '99.148.48.236'],
    // Add more services as needed
];

$ping = -1;
$responseTime = -1;
$cacheFile = 'cache_' . $service . '.json';
$cacheDuration = 60; // Cache duration in seconds (1 minute)

if (file_exists($cacheFile)) {
    $cacheData = json_decode(file_get_contents($cacheFile), true);
    if (time() - $cacheData['timestamp'] < $cacheDuration) {
        $ping = $cacheData['ping'];
        $responseTime = $cacheData['responseTime'];
        $uptime = $cacheData['uptime'];

        // Debugging information
        error_log("Cache hit: ping=$ping, responseTime=$responseTime, uptime=$uptime");
    } else {
        // Debugging information
        error_log("Cache expired");
    }
} else {
    // Debugging information
    error_log("Cache file not found");
}

if ($ping == -1 && array_key_exists($service, $services)) {
    $pingResult = ping($services[$service]['host'], 5);
    $ping = $pingResult['responseTime'];
    $responseTime = $pingResult['responseTime'];

    // Debugging information
    error_log("Ping result: ping=$ping, responseTime=$responseTime");
}

if ($ping == -1 && array_key_exists($service, $services)) {
    $pingResult = ping($services[$service]['host'], 5);
    $ping = $pingResult['responseTime'];
    $responseTime = $pingResult['responseTime'];

    // Load uptime data from a file (or database)
    $uptimeDataFile = 'uptime_data.json';
    $uptimeData = [];
    if (file_exists($uptimeDataFile)) {
        $uptimeData = json_decode(file_get_contents($uptimeDataFile), true);
    }

    // Calculate uptime percentage
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
        'responseTime' => $responseTime,
        'uptime' => $uptime
    ];
    file_put_contents($cacheFile, json_encode($cacheData));
}

// Load historical uptime data for the graph
$historicalDataFile = 'historical_uptime_' . $service . '.json';
$historicalData = [];
if (file_exists($historicalDataFile)) {
    $historicalData = json_decode(file_get_contents($historicalDataFile), true);
} else {
    // Initialize with empty data if file doesn't exist
    $historicalData = array_fill(0, 24, 100); // 24 hours of 100% uptime
}

// Update historical data
array_shift($historicalData); // Remove the oldest entry
$historicalData[] = $uptime; // Add the latest uptime
file_put_contents($historicalDataFile, json_encode($historicalData));

// Load issues from a database
$conn = new mysqli('localhost', 'lumihost_status', 'uZKwgga7z6qQZSNMcPdQ', 'lumihost_status');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$issues = $conn->query("SELECT * FROM issues WHERE status != 'closed'");
if ($issues === false) {
    die("Query failed: " . $conn->error);
}
$issues = $issues->fetch_all(MYSQLI_ASSOC);
$conn->close();

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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        body {
            color: white;
        }
        .issues-list table th,
        .issues-list table td {
            color: #ffffff !important;
        }
        .apexcharts-menu {
            background-color: #1a1d29 !important;
            color: #ffffff !important;
        }
        .apexcharts-menu-item:hover {
            background-color: #1a1d29 !important;
        }
    </style>
</head>

<body class="dark-theme">
    <header class="hero page">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                <h6 class="text-uppercase text-muted"><?php echo ucfirst($service); ?> Status</h6>
                <h4 class="font-weight-bold"><?php echo ucfirst($service); ?> Details<span class="main">.</span></h4>
            </div>
            <div class="status-details text-center dark-background p-4 rounded">
                <p class="lead">Uptime: <?php echo $uptime; ?>%</p>
                <p class="lead">Ping: <?php echo $responseTime >= 0 ? $responseTime . ' ms' : 'N/A'; ?></p>
                <div id="uptimeChart"></div>
                <div class="legend mt-4">
                    <span class="legend-item" style="color: green;">&#9632; 99% and above</span>
                    <span class="legend-item" style="color: orange;">&#9632; 95% - 98.99%</span>
                    <span class="legend-item" style="color: red;">&#9632; Below 95%</span>
                </div>
                <a href="index.php" class="btn btn-primary mt-4">Back to Status Page</a>
            </div>
        </div>
    </section>

    <section id="issues">
        <div class="container mt-5">
            <div class="section-title text-center">
                <h6 class="text-uppercase text-muted">Issues</h6>
                <h4 class="font-weight-bold">Reported Issues<span class="main">.</span></h4>
            </div>
            <div class="issues-list text-center dark-background p-4 rounded">
                <?php if (empty($issues)): ?>
                    <p class="lead">No issues reported.</p>
                <?php else: ?>
                    <table class="table table-bordered table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service</th>
                                <th>Issue</th>
                                <th>Status</th>
                                <th>Reported At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($issues as $issue): ?>
                            <tr>
                                <td><?php echo $issue['id']; ?></td>
                                <td><?php echo $issue['service']; ?></td>
                                <td><?php echo $issue['issue']; ?></td>
                                <td><?php echo $issue['status']; ?></td>
                                <td><?php echo $issue['reported_at']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-col">
                        <h5>Navigation<span class="main">.</span></h5>
                        <ul class="list-unstyled">
                            <li><a href="../index.php" class="text-white">Home</a></li>
                            <li><a href="../about.html" class="text-white">About</a></li>
                            <li><a href="../apply.html" class="text-white">Apply</a></li>
                            <li><a href="../staff.php" class="text-white">Staff Center</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-col">
                        <h5>Login<span class="main">.</span></h5>
                        <ul class="list-unstyled">
                            <li><a href="https://gamepanel.lumihost.net" class="text-white">Game Panel</a></li>
                            <li><a href="https://webpanel.lumihost.net:2222" class="text-white">Web Panel</a></li>
                            <li><a href="https://billing.lumihost.net" class="text-white">Billing Panel</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-col">
                        <h5>Legal<span class="main">.</span></h5>
                        <ul class="list-unstyled">
                            <li><a href="../tos.php" class="text-white">Terms of Service</a></li>
                            <li><a href="../pp.php" class="text-white">Privacy Policy</a></li>
                            <li><a href="../cookies.php" class="text-white">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <h6 class="mb-0">Copyright © 2025 Lumi Host. | All Rights Reserved |</h6>
        </div>
    </footer>

    <script src="assets/js/jquery.slim.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="assets/js/aos.js"></script>
    <script>
        AOS.init({
            duration: 1200,
        });

        // JavaScript to render the uptime chart using ApexCharts
        document.addEventListener("DOMContentLoaded", function() {
            const historicalData = <?php echo json_encode($historicalData); ?>;
            const labels = Array.from({ length: historicalData.length }, (_, i) => i + 1);

            var options = {
                series: [{
                    name: 'Uptime (%)',
                    data: historicalData
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                },
                plotOptions: {
                    bar: {
                        colors: {
                            ranges: [{
                                from: 0,
                                to: 94.99,
                                color: 'red'
                            }, {
                                from: 95,
                                to: 98.99,
                                color: 'orange'
                            }, {
                                from: 99,
                                to: 100,
                                color: 'green'
                            }]
                        },
                        columnWidth: '50%',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: labels,
                },
                yaxis: {
                    max: 100,
                    min: 0,
                },
                tooltip: {
                    theme: 'dark'
                }
            };

            var chart = new ApexCharts(document.querySelector("#uptimeChart"), options);
            chart.render();
        });
    </script>
</body>

</html>