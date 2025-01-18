<?php
$service = $_GET['service'] ?? 'unknown';

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
    'lumi_radio' => ['host' => '99.148.48.237', 'port' => 80],
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
    }
}

if ($ping == -1 && array_key_exists($service, $services)) {
    $pingResult = ping($services[$service]['host'], $services[$service]['port'], 10);
    $ping = $pingResult['responseTime'];
    $responseTime = $pingResult['responseTime'];

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
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'lumihost_status', 'uZKwgga7z6qQZSNMcPdQ', 'lumihost_status');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$issues = $conn->query("SELECT * FROM issues");
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <p>Ping: <?php echo $ping >= 0 ? round($ping) . ' ms' : 'Down'; ?> (Response Time: <?php echo round($responseTime); ?> ms)</p>
                <canvas id="uptimeChart" width="400" height="200"></canvas>
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
                <h6>Issues</h6>
                <h4>Reported Issues<span class="main">.</span></h4>
            </div>
            <div class="issues-list text-center dark-background">
                <?php if (empty($issues)): ?>
                    <p>No issues reported.</p>
                <?php else: ?>
                    <table class="table table-bordered">
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

        // JavaScript to render the uptime chart
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('uptimeChart').getContext('2d');
            const historicalData = <?php echo json_encode($historicalData); ?>;
            const labels = Array.from({ length: historicalData.length }, (_, i) => i + 1);

            const data = {
                labels: labels,
                datasets: [{
                    label: 'Uptime (%)',
                    data: historicalData,
                    backgroundColor: historicalData.map(value => {
                        if (value >= 99) return 'green';
                        if (value >= 95) return 'orange';
                        return 'red';
                    }),
                    borderColor: 'black',
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            };

            new Chart(ctx, config);
        });
    </script>
</body>

</html>